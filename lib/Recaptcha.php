<?php
/// <module name="Service.Recaptcha" maintainer="svistunov@techart.ru" version="0.1.0">
///   <brief>Модуль для использования Recaptcha</brief>
///   <details>
///   Модуль содержит единственный класс Client, на вход которому передается публичный и частный ключи,
///   а также необязательный параметр proxy для задания прокси-сервера.
///   Метод is_valid производит основную работу -- посылает запрос на сервер Recapthca, если возникла
///   ошибка, то строка ошибки сохраняется в свойстве error.
///   Метод html рисует форму Recaptcha.
///   </details>
Core::load('Net.Agents.HTTP');

/// <class name="Service.Recaptcha" stereotype="module">
///   <implements interface="Core.ModuleInterface" />
class Service_Recaptcha implements Core_ModuleInterface {
///   <constants>
  const VERSION = '0.1.0';
  const SERVER_URL = 'http://api.recaptcha.net';
  const SECURE_SERVER_URL = 'https://api-secure.recaptcha.net';
  const VERIFY_SERVER_URL = 'api-verify.recaptcha.net/verify';
///   </constants>

///   <protocol name="building">

///   <method name="Client" returns="Service.Recaptcha.Client" scope="class">
///     <args>
///       <arg name="pubkey" type="string" />
///       <arg name="privkey" type="string" />
///       <arg name="proxy" type="string" />
///     </args>
///     <body>
  static public function Client($pubkey, $privkey, $proxy = null) {
    return new Service_Recaptcha_Client($pubkey, $privkey, $proxy);
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>

/// <class name="Service.Recaptcha.Exception" extends="Core.Exception">
class Service_Recaptcha_Exception extends Core_Exception {}
/// </class>

/// <class name="Service.Recaptcha.Client">
///   <implements interface="Core.StringifyInterface" />
///   <implements interface="Core.PropertyAccessInterface" />
class Service_Recaptcha_Client
  implements Core_StringifyInterface, Core_PropertyAccessInterface {

  protected $pubkey  = null;
  protected $privkey = null;
  protected $error   = null;
  protected $proxy = null;
  protected $agent = null;

///   <protocol name="creating">
///   <method name="__construct">
///     <args>
///       <arg name="pubkey" type="string" />
///       <arg name="privkey" type="string" />
///       <arg name="proxy" type="string" default="null" />
///     </args>
///     <body>
  public function __construct($pubkey, $privkey, $proxy = null) {
    $this->pubkey  = (string) $pubkey;
    $this->privkey = (string) $privkey;
    $this->proxy = (string) $proxy;
    $this->agent = Net_Agents_HTTP::Agent();
  }
///     </body>
///   </method>
///   </protocol>

///   <protocol name="configuration">

///   <method name="use_agent">
///     <args>
///       <arg name="agent" type="" />
///     </args>
///     <body>
  public function use_agent(Net_HTTP_AgentInterface $agent) {
    $this->agent = $agent;
    return $this;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="supporting">

///   <method name="is_valid" returns="boolean">
///     <args>
///       <arg name="request" type="Net.HTTP.Request" />
///     </args>
///     <body>
  public function is_valid(Net_HTTP_Request $request) {
    if ($this->privkey == null)
      throw new Service_Recaptcha_Exception("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
    if ($request->meta['REMOTE_ADDR'] == null || $request->meta['REMOTE_ADDR'] == '')
      throw new Service_Recaptcha_Exception('For security reasons, you must pass the remote ip to reCAPTCHA');
    if ($request['recaptcha_challenge_field'] == null ||
        strlen($request['recaptcha_challenge_field']) == 0 ||
        $request['recaptcha_response_field'] == null ||
        strlen($request['recaptcha_response_field']) == 0) {
          $this->error = 'Введите текст изображенный на картинке';
          return false;
        }
    $response =
      $this->agent->
        using_proxy($this->proxy)->
        timeout(20)->
        send(Net_HTTP::Request(Service_Recaptcha::VERIFY_SERVER_URL)->
          method('post')->
          parameters(array('privatekey' => $this->privkey,
            'remoteip' => $request->meta['REMOTE_ADDR'],
            'challenge' => $request['recaptcha_challenge_field'],
            'response' => $request['recaptcha_response_field'])
          )
        );

    $answers = explode ("\n", $response->body);
    if (trim($answers[0]) == 'true')
      return true;
    else {
      $this->error = $answers[1];
      return false;
    }
  }
///     </body>
///   </method>

///   <method name="html" returns="string">
///     <args>
///       <arg name="use_ssl" type="booblean" default="false" />
///     </args>
///     <body>
  public function html($use_ssl = false) {
    if ($this->pubkey == null)
      throw new Service_Recaptcha_Exception("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");

    $server = $use_ssl ? Service_Recaptcha::SECURE_SERVER_URL : Service_Recaptcha::SERVER_URL;
    $errorpart = $this->error === null ? '' : "&amp;error=" . $this->error;

    return
    '<script type="text/javascript" src="'. $server . '/challenge?k=' . urlencode($this->pubkey . $errorpart) . '"></script>

    <noscript>
        <iframe src="'. $server . '/noscript?k=' . urlencode($this->pubkey . $errorpart) . '" height="300" width="500" frameborder="0"></iframe><br/>
        <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
        <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
    </noscript>';
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="stringifying">

///   <method name="as_string" returns="string">
///     <body>
  public function as_string() {
    return $this->html();
  }
///     </body>
///   </method>

///   <method name="__toString" returns="string">
///     <body>
  public function __toString() {
    return $this->as_string();
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="accessing">

///   <method name="__get" returns="mixed">
///     <args>
///       <arg name="property" type="string" />
///     </args>
///     <body>
  public function __get($property) {
    switch ($property) {
      case 'error': case 'pubkey': case 'privkey': return $this->$property;
      default: throw new Core_MissingPropertyException($property);
    }
  }
///     </body>
///   </method>

///   <method name="__set" returns="mixed">
///     <args>
///       <arg name="property" type="string" />
///       <arg name="value" />
///     </args>
///     <body>
  public function __set($property, $value) {
    throw new Core_ReadOnlyPropertyException($property);
  }
///     </body>
///   </method>

///   <method name="__isset" returns="boolean">
///     <args>
///       <arg name="property" type="string" />
///     </args>
///     <body>
  public function __isset($property) {
    switch ($property) {
      case 'error':
      case 'pubkey':
      case 'privkey':
        return isset($this->$property);
      default:
        return false;
    }
  }
///     </body>
///   </method>

///   <method name="__unset">
///     <args>
///       <arg name="property" type="string" />
///     </args>
///     <body>
  public function __unset($property) {
    throw new Core_ReadOnlyPropertyException($property);
  }
///     </body>
///   </method>

///   </protocol>


}
/// </class>

/// </module>
?>