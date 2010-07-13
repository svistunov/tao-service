<?php
/// <module name="Test.Service.Google.AdWords" version="0.1.0" maintainer="svistunov@techart.ru">
Core::load('Dev.Unit', 'Service.Google.AdWords', 'Test.Service.Google.Auth');

/// <class name="Test.Service.Google.AdWords" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_Google_AdWords implements Dev_Unit_TestModuleInterface {

///   <constants>
  const VERSION = '0.1.0';
///   </constants>

///   <protocol name="testing">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.Google.AdWords.',
    'ClientCase', 'ServiceCase', 'OperationsCase', 'ObjectCase', 'EntityCase');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.ClientCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_AdWords_ClientCase extends Dev_Unit_TestCase {
  protected $client;
  protected $auth;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  public function setup() {
    $this->client = Service_Google_AdWords::Client(
      $this->auth = Service_Google_Auth::ClientLogin()->agent(new Test_Service_Google_Auth_Agent())->
        login('test@gmail.com', '1234'),
      array('user_agent' => 'user agent')
    );
  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
 public function test_accessing() {
    $this->client->
      header('developer_token', 'AAAAAAAAAAAAAAA')->
      headers(array('client_email' => 'client_1+test@gmail.com'))->
      use_sandbox();
    $this->asserts->accessing->
      assert_read($this->client, $r = array(
        'is_sandbox' => true,
        'headers' => array(
          'userAgent' => 'user agent',
          'authToken' => $this->auth->token,
          'developerToken' => 'AAAAAAAAAAAAAAA',
          'clientEmail' => 'client_1+test@gmail.com'
        ),
        'units' => 0
      ))->
      assert_write($this->client, $w = array(
        'is_sandbox' => false,
        'headers' => array(
          'userAgent' => 'new',
          'authToken' => 'new',
          'developerToken' => 'new',
          'clientEmail' => 'new'
        )
      ))->
      assert_read_only($this->client, array('units' => 0, 'services' => array()))->
      assert_undestroyable($this->client, array_keys($r));
  }
///     </body>
///   </method>

///   <method name="test_services">
///     <body>
  public function test_services() {
    $campaign = $this->client->campaign;
    $bulk = $this->client->bulk_mutate_job;

    $this->
      assert_equal(
        $this->client->services,
        array('campaign' => $campaign, 'bulk_mutate_job' => $bulk)
      )->
      assert_class('Service.Google.AdWords.Service', $campaign)->
      assert_class('Service.Google.AdWords.Service', $bulk)->
      assert_equal($campaign->wsdl, 'https://adwords.google.com/api/adwords/cm/v200909/CampaignService?wsdl')->
      assert_equal($bulk->wsdl, 'https://adwords.google.com/api/adwords/job/v200909/BulkMutateJobService?wsdl')
      ;
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.SoapClient">
class Test_Service_Google_AdWords_SoapClient {
  public $wsdl;
  public $options;
  public $headers;

  public $called_methods = array();

///   <protocol name="creating">

///   <method name="__construct">
///     <args>
///       <arg name="wsdl" type="" />
///       <arg name="options" type="array" defaults="array()" />
///       <arg name="headers" type="array" defaults="array()" />
///     </args>
///     <body>
  public function __construct($wsdl, $options= array(), $headers = array()) {
    $this->wsdl = $wsdl;
    $this->options = $options;
    $this->headers = $headers;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="performing">

///   <method name="__soapCall">
///     <args>
///       <arg name="func_name" type="" />
///     </args>
///     <body>
  public function __soapCall($function_name, array $arguments, $options = null, $input_headers = null, &$outpu_headers = null) {
    $this->called_methods['__soapCall'][] = array(
      'function_name' => $function_name,
      'arguments' => $arguments,
      'options' => $options,
      'input_headers' => $input_headers,
      'output_headers' => $outpu_headers
    );
    $outpu_headers['ResponseHeader'] = (object) array('units' => 2);
    return (object) array('rval' => 'ok');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.Service" extends="Service.Google.AdWords.Service" >
class Test_Service_Google_AdWords_Service extends Service_Google_AdWords_Service {
  public $called_methods = array();
///   <protocol name="creating">

///   <method name="setup" returns="Service.Google.AdWords.Service" access="protected">
///     <args>
///       <arg name="client" type="Service.Google.AdWords.Client" />
///     </args>
///     <body>
  protected function setup(Service_Google_AdWords_Client $client) {
    $this->called_methods['setup'][] = array('client' => $client);
    return $this;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="supporting">

///   <method name="build_soap_client">
///     <args>
///       <arg name="wsdl" type="" />
///       <arg name="options" type="array" defaults="array()" />
///       <arg name="headers" type="array" defaults="array()" />
///     </args>
///     <body>
  protected function build_soap_client($wsdl, $options= array(), $headers = array()) {
    return new Test_Service_Google_AdWords_SoapClient($wsdl, $options, $headers);
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.ServiceCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_AdWords_ServiceCase extends Dev_Unit_TestCase {
  protected $client;
  protected $auth;
  protected $service;

///   <protocol name="testing">

///   <method name="setup">
///     <body>
  public function setup() {
    $this->client = Service_Google_AdWords::Client(
      $this->auth = Service_Google_Auth::ClientLogin()->agent(new Test_Service_Google_Auth_Agent())->
        login('test@gmail.com', '1234'),
      array('user_agent' => 'user agent')
    );
    $this->service = new Test_Service_Google_AdWords_Service(
      $this->client, 'wsdl/test/url',
      array('Budget' => 'MyBudget', 'Wsdl_Class' => 'PHP_Class'),
      array('trace' => false)
    );
  }
///     </body>
///   </method>

///   <method name="test_creating">
///     <body>
  public function test_creating() {
    $this->asserts->accessing->
      assert_equal(
        $this->service->called_methods['setup'][0],
        array('client' => $this->client)
      )->
      assert_read($this->service->soap, array(
        'wsdl' => 'wsdl/test/url',
        'options' => array(
          'classmap' =>
            array (
              'Campaign' => 'Service_Google_AdWords_Campaign',
              'Budget' => 'MyBudget',
              'LanguageTarget' => 'Service_Google_AdWords_LanguageTarget',
              'NetworkTarget' => 'Service_Google_AdWords_NetworkTarget',
              'AdScheduleTarget' => 'Service_Google_AdWords_AdScheduleTarget',
              'CityTarget' => 'Service_Google_AdWords_CityTarget',
              'GeoTarget' => 'Service_Google_AdWords_GeoTarget',
              'CountryTarget' => 'Service_Google_AdWords_CountryTarget',
              'MetroTarget' => 'Service_Google_AdWords_MetroTarget',
              'ProximityTarget' => 'Service_Google_AdWords_ProximityTarget',
              'AdGroup' => 'Service_Google_AdWords_AdGroup',
              'Ad' => 'Service_Google_AdWords_Ad',
              'Image' => 'Service_Google_AdWords_Image',
              'Video' => 'Service_Google_AdWords_Video',
              'ApiError' => 'Service_Google_AdWords_ApiError',
              'Wsdl_Class' => 'PHP_Class'
            ),
          'trace' => false,
          'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        ),
        'headers' => array('userAgent' => 'user agent', 'authToken' => $this->auth->token)
      ))
      ;
  }
///     </body>
///   </method>

///   <method name="test_get">
///     <body>
  public function test_get() {
    $res = $this->service->get($s =
      Service_Google_AdWords::Selector()->
        ids('101287')->
        campaign_statuses('PAUSED')->
        stats_selector(
          Service_Google_AdWords::Selector()->
          date_range(array('min' => Time::DateTime('2010-01-01'), 'max' => Time::DateTime('2011-01-01')))
        )
    );

    $this->
      assert_equal($res, 'ok')->
      assert_equal($this->service->units, 2)->
      assert_equal(
        $this->service->soap->called_methods['__soapCall'][0],
        array(
          'function_name' => 'get',
          'arguments' => array(array('selector' => $s->as_array())),
          'options' => array(),
          'input_headers' => array(),
          'output_headers' => null
        )
      );
  }
///     </body>
///   </method>

///   <method name="test_mutate">
///     <body>
  public function test_mutate() {
    $res = $this->service->mutate($operations =
      Service_Google_AdWords::Operations()->
        add(
          Service_Google_AdWords::Campaign()->
          name('Interplanetary Cruise #' . time())->
          status('PAUSED')->
          //явный вызов __value() нужет только в тесте
          bidding_strategy(Service_Google_AdWords::Entity('ManualCPC')->__value)->
          budget(
            Service_Google_AdWords::Entity()->
            period('DAILY')->
            amount(array('micro_amount' => 50000000))->
            delivery_method('STANDARD')
          )->__value
        )
    );

    $this->
      assert_equal($res, 'ok')->
      assert_equal($this->service->units, 2)->
      assert_equal(
        $this->service->soap->called_methods['__soapCall'][0],
        array(
          'function_name' => 'mutate',
          'arguments' => array(array('operations' => $operations->for_soap())),
          'options' => array(),
          'input_headers' => array(),
          'output_headers' => null
        )
      );
  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
  public function test_accessing() {
    $this->asserts->accessing->
      assert_read(
        $this->service,
        array('wsdl' => 'wsdl/test/url'))->
      assert_write(
        $this->service,
        array('wsdl' => 'wsdl/news/url'))->
      assert_read_only(
        $this->service,
        array('units' => 0))->
      assert_exists_only($this->service, array('soap'))
    ;
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.OperationsCase" extends="Test.Dev.TestCase">
class Test_Service_Google_AdWords_OperationsCase extends Dev_Unit_TestCase {
  protected $operations;
  protected $soap_var;
  protected $add_element;
  protected $set_element;
  protected $remove_element;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  public function setup() {
    $this->operations = Service_Google_AdWords::Operations()->
      add(
          $this->add_element = Service_Google_AdWords::Entity()->
          name('Interplanetary Cruise')->
          status('PAUSED')->
          //явный вызов __value() нужет только в тесте
          bidding_strategy($this->soap_var = Service_Google_AdWords::Entity('ManualCPC')->__value)->
          budget(
            Service_Google_AdWords::Entity()->
            period('DAILY')->
            amount(array('micro_amount' => 50000000))->
            delivery_method('STANDARD')
          )
        )->
      remove(
        $this->remove_element = Service_Google_AdWords::Entity()->
          key('value')
      )->
      set(
        $this->set_element = Service_Google_AdWords::Entity()->
          key('value')
      )
      ;
  }
///     </body>
///   </method>

///   <method name="test_for_soap">
///     <body>
  public function test_for_soap() {
    $this->
      assert_equal(
        $this->operations->for_soap(),
        array (
          0 =>
          array (
            'operator' => 'ADD',
            'operand' =>
            array (
              'name' => 'Interplanetary Cruise',
              'status' => 'PAUSED',
              'biddingStrategy' => $this->soap_var,
              'budget' =>
              array (
                'period' => 'DAILY',
                'amount' =>
                array (
                  'microAmount' => 50000000,
                ),
                'deliveryMethod' => 'STANDARD',
              ),
            ),
          ),
          1 =>
          array (
            'operator' => 'REMOVE',
            'operand' =>
            array (
              'key' => 'value',
            ),
          ),
          2 =>
          array (
            'operator' => 'SET',
            'operand' =>
            array (
              'key' => 'value',
            ),
          ),
        )
      );
  }
///     </body>
///   </method>

///   <method name="test_indexing_iterating">
///     <body>
  public function test_indexing_iterating() {
    $this->asserts->indexing->
      assert_read_only( $this->operations, $ro = array(
        0 => array('operator' => 'ADD', 'operand' => $this->add_element),
        1 => array('operator' => 'REMOVE', 'operand' => $this->remove_element),
        2 => array('operator' => 'SET', 'operand' => $this->set_element)
      ));
    $this->asserts->iterating->
      assert_read($this->operations, $ro);
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.ObjectCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_AdWords_ObjectCase extends Dev_Unit_TestCase {
  protected $object;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  public function setup() {
    $this->object = Service_Google_AdWords::Entity('' ,array('construct' => 'value'))->
      to_camel_case('camelValue')->
      test_date(Time::DateTime('2010-12-31'))->
      update_with(array('update_with' => 'update value'))->
      test_nested_object(Service_Google_AdWords::Entity('', array('key' => 'value')))->
      test_nested_array(array('to_camel_case' => 'value'))
      ;
  }
///     </body>
///   </method>

///   <method name="test_as_array">
///     <body>
  public function test_as_array() {
    $this->assert_equal(
      $this->object->as_array(),
      array (
        'construct' => 'value',
        'toCamelCase' => 'camelValue',
        'testDate' => '20101231',
        'updateWith' => 'update value',
        'testNestedObject' =>
        array (
          'key' => 'value',
        ),
        'testNestedArray' =>
        array (
          'toCamelCase' => 'value',
        ),
      )
    );
  }
///     </body>
///   </method>

///   <method name="test_indexing_iterating">
///     <body>
  public function test_indexing_iterating() {
    $this->asserts->iterating->
      assert_read($this->object, $r = array(
        'construct' => 'value',
        'toCamelCase' => 'camelValue',
        'testDate' => '20101231',
        'updateWith' => 'update value',
        'testNestedObject' => Service_Google_AdWords::Entity('', array('key' => 'value')),
        'testNestedArray' => array ('to_camel_case' => 'value')
      ));

    $this->asserts->indexing->
      assert_read($this->object, $r)->
      assert_write($this->object, array('new_key' => 'new value'));
  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
  public function test_accessing() {
    $this->asserts->accessing->
      assert_read($this->object, $r = array(
        'construct' => 'value',
        'to_camel_case' => 'camelValue',
        'test_date' => Time::DateTime('20101231'),
        'update_with' => 'update value',
        'test_nested_object' => Service_Google_AdWords::Entity('', array('key' => 'value')),
        'test_nested_array' => array ('to_camel_case' => 'value')
      ))->
      assert_write($this->object, array('new_key' => 'new value'));
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.AdWords.EntityCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_AdWords_EntityCase extends Dev_Unit_TestCase {
  protected $entity;
  protected $nested_entity;
  protected $nested_value;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  public function setup() {
    //TODO: организовать нормальное сравнение вложенного enity и процедуру сравнения SoapVar
    $this->nested_entity = Service_Google_AdWords::Entity('TestType', array('key' => 'value'));
    $this->entity = Service_Google_AdWords::Campaign(array('construct' => 'value'))->
      test_nested_entity($this->nested_value = $this->nested_entity->__value)
      ;
  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
  public function test_accessing() {
    $this->
      assert_equal($this->entity->__type, 'Campaign')->
      assert_equal($this->nested_entity->__type, 'TestType')->
      assert_true((boolean) $this->entity->__type('NewType'))->
      assert_equal($this->entity->__type, 'NewType')->
      assert_class('SoapVar', $this->entity->__value);
  }
///     </body>
///   </method>

///   <method name="test_as_array">
///     <body>
  public function test_as_array() {
    $this->assert_equal(
      $this->entity->as_array(),
       array (
        'construct' => 'value',
        'testNestedEntity' => $this->nested_value
      )
    );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// </module>
?>