<?php
/// <module name="Test.Service.Recaptcha" version="0.1.0" maintainer="svistunov@techart.ru">
Core::load('Dev.Unit', 'Service.Recaptcha');

/// <class name="Test.Service.Recaptcha" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_Recaptcha implements Dev_Unit_TestModuleInterface {

///   <constants>
  const VERSION = '0.1.0';
///   </constants>

///   <protocol name="testing">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.Recaptcha.', 'RecaptchaCase');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Recaptcha.Agent">
///   <implements interface="Net.HTTP.AgentInterface" />
///   <implements interface="Core.CallInterface" />
class Test_Service_Recaptcha_Agent implements Net_HTTP_AgentInterface, Core_CallInterface {
  public $called_methods = array();
  public $response = null;

///   <protocol name="performing">
///   <method name="send" returns="Net.HTTP.Response">
///     <args>
///       <arg name="request" type="Net.HTTP.Request"/>
///     </args>
///     <body>
  public function send(Net_HTTP_Request $request) {
    $this->called_methods['send'][] = array('request' => $request);
    return $this->response;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="calling">

///   <method name="__call">
///     <args>
///       <arg name="method" type="string" />
///       <arg name="args" type="array" />
///     </args>
///     <body>
  public function __call($method, $args) {
    $this->called_methods[$method][] = $args;
    return $this;
  }
///     </body>
////  </method>

///   </protocol>

}
/// </class>

/// <class name="Test.Service.Recaptcha.RecaptchaCase" extends="Dev.Unit.TestCase">
class Test_Service_Recaptcha_RecaptchaCase extends Dev_Unit_TestCase {
  protected $rcap;
///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
   $this->rcap = Service_Recaptcha::Client('test pub key', 'test priv key', 'proxy');
  }
///     </body>
///   </method>

///   <method name="test_all">
///     <body>
 public function test_creating() {
    $this->asserts->accessing->
      assert_read_only($this->rcap, array(
        'pubkey' => 'test pub key',
        'privkey' => 'test priv key'
      ));
  }
///     </body>
///   </method>

///   <method name="test_html">
///     <body>
  public function test_html() {
    $r = Service_Recaptcha::Client('','');
    $this->set_trap();
    try{
      $r->html();
    } catch (Service_Recaptcha_Exception $e) {
      $this->trap($e);
    }
    $this->assert_true($this->is_catch_prey());

    $this->assert_equal($this->rcap->html(), $this->rcap->as_string());

    $this->assert_same($this->rcap->html(true),
<<<HTML
<script type="text/javascript" src="https://api-secure.recaptcha.net/challenge?k=test+pub+key">
</script>
<noscript>
<iframe src="https://api-secure.recaptcha.net/noscript?k=test+pub+key" height="300" width="500" frameborder="0">
</iframe><br/>
<textareaname="recaptcha_challenge_field"rows="3"cols="40">
</textarea>
<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
</noscript>
HTML
    );
  }
///     </body>
///   </method>

///   <method name="test_is_valid">
///     <body>
  public function test_is_valid() {
    $agent = new Test_Service_Recaptcha_Agent();
    $this->rcap->use_agent($agent);
    $agent->response = Net_HTTP::Response("false\nError message");

    $this->set_trap();
    try{
      $this->rcap->is_valid(Net_HTTP::Request());
    } catch (Service_Recaptcha_Exception $e) {
      $this->trap($e);
    }
    $this->
      assert_true($this->is_catch_prey())->
      assert_equal(
        $this->exception->message,
        'For security reasons, you must pass the remote ip to reCAPTCHA'
      );

    $request = Net_HTTP::Request('http://test.com/', array('REMOTE_ADDR' => '192.168.0.1'))->
      parameters(array(
        'recaptcha_challenge_field' => 'test_challenge_field',
        'recaptcha_response_field' => 'test_response_field'
      ));
    $this->
      assert_false($this->rcap->is_valid($request))->
      assert_equal($this->rcap->error, 'Error message');

    $agent->response = Net_HTTP::Response("true\n");
    $this->
      assert_true($this->rcap->is_valid($request));

    $this->asserts->accessing->
      assert_read($agent, array(
        'called_methods' => array(
          'using_proxy' => array(array('proxy'), array('proxy')),
          'send' => array(array('request' => $request), array('request' => $request)),
          'timeout' => array(array(20), array(20))
        )
      ));

  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// </module>
?>