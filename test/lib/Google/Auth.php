<?php
/// <module name="Test.Service.Google.Auth" version="0.1.0" maintainer="svistunov@techart.ru">
Core::load('Dev.Unit', 'Service.Google.Auth');

/// <class name="Test.Service.Google.Auth" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_Google_Auth implements Dev_Unit_TestModuleInterface {

///   <constants>
  const VERSION = '0.1.0';
///   </constants>

///   <protocol name="testing">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.Google.Auth.', 'ClientLoginCase');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.Auth.Agent" >
///   <implements interface="Net.HTTP.AgentInterface" />
class Test_Service_Google_Auth_Agent implements Net_HTTP_AgentInterface {
  public $request;
///   <protocol name="performing">

///   <method name="send" returns="Net.HTTP.Response">
///     <brief>Отправляет запрос и возвращает отклик в виде объекта класса Net.HTTP.Response</brief>
///     <args>
///       <arg name="request" type="Net.HTTP.Request" brief="запрос" />
///     </args>
///     <body>
  public function send(Net_HTTP_Request $request) {
    $this->request = $request;
    return Net_HTTP::Response()->from_string(
<<<EOF
HTTP/1.1 200 OK
Content-Type: text/plain
Cache-control: no-cache, no-store
Pragma: no-cache
Expires: Mon, 01-Jan-1990 00:00:00 GMT
Date: Thu, 11 Mar 2010 06:52:53 GMT
X-Content-Type-Options: nosniff
Content-Length: 563
Server: GSE
X-XSS-Protection: 0

SID=DQAAAH8AAAAEK-1mOSeTSgaPuKB-gxLQ4zY2N3Y5B5iCmpeS7BA5sD2RM4EG-oh0GyUmFPoszzmkuGmiG44YS_CZQCz-1QpzwiG4ox0agj_-BBvvTJe447WkNd1rleHuBpFUmQMWuYjswIiWAcrcRivHf_kNW_lyw3U00do2BbGzQXm3W-2jGw
LSID=DQAAAIAAAADyMERJGcz7feLz4mOB4Ge-CGIAPlsgoiLwb2LunA3_9sb5eTWgHbK3w562bkffvwLNB58X_GKOB-_tXk8_AikeydvcPFGwR7jWiLuBnN-6pbQxVwpuKj5RFb8aj1x_EQaetzE7O1bugLDpZ0U9nEvOYxB-ym_M_jIMwZ_j9loy9Q
Auth=DQAAAIAAAADyMERJGcz7feLz4mOB4Ge-CGIAPlsgoiLwb2LunA3_9sb5eTWgHbK3w562bkffvwLNB58X_GKOB-_tXk8_AikevJOmFq2rK33mBnHRF0v0Xs6YNQWmAnTvcIZ7y1LYFgLnHc6qAQ5C19zLPR2Jdvzj1hWjHPakGfzsM79nuzMTxA
EOF
    );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.Auth.ClientLoginCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_Auth_ClientLoginCase extends Dev_Unit_TestCase {
  protected $auth;
  protected $agent;
///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
    $this->auth = Service_Google_Auth::ClientLogin()->agent($this->agent = new Test_Service_Google_Auth_Agent());
  }
///     </body>
///   </method>

///   <method name="test_all">
///     <body>
  public function test_all() {
    $this->auth->
      service('adwords')->
      account_type('GOOGLE')->
      login('test@gmail.com', '1234');
    $this->asserts->indexing->
      assert_read($this->agent->request, array(
        'Email' => 'test@gmail.com',
        'Passwd' => '1234',
        'service' => 'adwords',
        'accountType' => 'GOOGLE',
        'source' => Service_Google_Auth::DEFAULT_SOURCE
      ))->
      assert_equal($this->agent->request->method, Net_HTTP::POST)->
      assert_equal($this->auth->token, 'DQAAAIAAAADyMERJGcz7feLz4mOB4Ge-CGIAPlsgoiLwb2LunA3_9sb5eTWgHbK3w562bkffvwLNB58X_GKOB-_tXk8_AikevJOmFq2rK33mBnHRF0v0Xs6YNQWmAnTvcIZ7y1LYFgLnHc6qAQ5C19zLPR2Jdvzj1hWjHPakGfzsM79nuzMTxA');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// </module>
?>