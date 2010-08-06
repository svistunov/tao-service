<?php
/// <module name="Test.Service.Yandex.Direct" version="0.1.0" maintainer="timokhin@techart.ru">
Core::load('Dev.Unit', 'Service.OpenSocial');

/// <class name="Test.Service.OpenSocial" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_OpenSocial
  implements Dev_Unit_TestModuleInterface, Service_OpenSocial_ModuleInterface {

///   <protocol name="creating">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.OpenSocial.',
      'ContainerTest',
      'OperationTest', 'ServiceTest', 'RequestTest', 'ResourceTest', 'CollectionTest');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.OpenSocial.ContainerTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_ContainerTest extends Dev_Unit_TestCase {

///   <protocol name="testing">

///   <method name="test_all">
///     <body>
  public function test_all() {
    $c = Service_OpenSocial::Container(
      $o = array(
        'name'                  => 'Google',
        'use_method_override'   => true,
        'use_request_body_hash' => true,
        'rest_endpoint' => 'http://www.google.com/friendconnect/api',
        'rpc_endpoint'  => 'http://www.google.com/friendconnect/api/rpc'));

    $this->
      assert($c instanceof Service_OpenSocial_Container);

    $this->
      asserts->
        accessing->
          assert_read($c, $o)->
          assert_write($c, array(
            'name' => 'GFC',
            'use_method_override' => false,
            'rest_endpoint' => 'http://www.google.com/gfc/api'))->
          assert_missing($c, array('unknown_option'));

    $c->use_method_override   = 0;
    $c->use_request_body_hash = 0;

    $this->
      assert_false($c->use_method_override)->
      assert_false($c->use_request_body_hash);

    $this->assert($c->name('GoogleFriendConnect')->name === 'GoogleFriendConnect');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


/// <class name="Test.Service.OpenSocial.OperationTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_OperationTest extends Dev_Unit_TestCase {

///   <protocol name="testing">

///   <method name="test_all">
///     <body>
  public function test_all() {
    $o = Service_OpenSocial_Operation::GET();
    $this->
      assert((string) $o === 'get')->
      assert($o->http_method === Net_HTTP::GET);

    $o = Service_OpenSocial_Operation::CREATE();
    $this->
      assert((string) $o === 'create')->
      assert($o->http_method === Net_HTTP::POST);

    $o = Service_OpenSocial_Operation::UPDATE();
    $this->
      assert((string) $o === 'update')->
      assert($o->http_method === Net_HTTP::PUT);

    $o = Service_OpenSocial_Operation::DELETE();
    $this->
      assert((string) $o === 'delete')->
      assert($o->http_method === Net_HTTP::DELETE);
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.OpenSocial.CollectionTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_CollectionTest extends Dev_Unit_TestCase {

///   <protocol name="testing">

///   <method name="test_all">
///     <body>
  public function test_all() {
    $c = Core::with(new Service_OpenSocial_Collection(3,2,1))->
        append($p0 = Service_OpenSocial::Person(array('id' => 1, 'name' => 'john')))->
        append($p1 = Service_OpenSocial::Person(array('id' => 2, 'name' => 'james')))->
        append($p2 = Service_OpenSocial::Person(array('id' => 3, 'name' => 'anna')));

    $this->
      assert(count($c) == 3)->
      assert_equal($c[0], $p0)->
      assert_equal($c[1], $p1)->
      assert_equal($c[2], $p2);

    $this->set_trap();
    try {
      $c[5] = Service_OpenSocial::Person();
    } catch (Core_ReadOnlyObjectException $e) {
      $this->trap($e);
    }

    $this->assert_exception();

    $this->set_trap();
    try {
      unset($c[0]);
    } catch (Core_ReadOnlyObjectException $e) {
      $this->trap($e);
    }

    $this->assert_exception();

    $this->
      asserts->
        accessing->
          assert_read_only($c, array(
            'items_per_page' => 2,
            'total_results'  => 3,
            'start_index'    => 1));
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>

/// <class name="Test.Service.OpenSocial.ServiceTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_ServiceTest extends Dev_Unit_TestCase {

///   <protocol name="testing">
///   <method name="test_all">
///     <body>
  public function test_all() {
    $s = Service_OpenSocial_Service::PEOPLE();
    $this->
      assert((string) $s === 'people')->
      assert($s->resource_class === 'Person')->
      assert($s->rpc_name === 'people');

    $s = Service_OpenSocial_Service::GROUP();
    $this->
      assert((string) $s === 'group')->
      assert($s->resource_class === 'Group')->
      assert($s->rpc_name === 'group');

    $s = Service_OpenSocial_Service::ACTIVITIES();
    $this->
      assert((string) $s === 'activities')->
      assert($s->resource_class === 'Activity')->
      assert($s->rpc_name === 'activity');;

    $s = Service_OpenSocial_Service::APPDATA();
    $this->
      assert((string) $s === 'appdata')->
      assert($s->resource_class === 'AppData')->
      assert($s->rpc_name === 'data');
  }
///     </body>
///   </method>
}
/// </class>


/// <class name="Test.Service.OpenSocial.RequestTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_RequestTest extends Dev_Unit_TestCase {

///   <protocol name="testing">

///   <method name="test_all">
///     <body>
  public function test_all() {
    $r = Service_OpenSocial::Request()->
      service(Service_OpenSocial_Service::PEOPLE)->
      operation(Service_OpenSocial_Operation::GET)->
      user_id('@me')->
      group_id('@friends')->
      auth('auth-token')->
      format('json');

    $this->
      assert($r instanceof Service_OpenSocial_Request)->
      assert($r->service instanceof Service_OpenSocial_Service)->
      assert($r->operation instanceof Service_OpenSocial_Operation);

    $this->
      asserts->
        accessing->
          assert_read($r, array(
            'user_id' => '@me',
            'group_id' => '@friends',
            'id' => '',
            'resource_id' => '',
            'resource' => null,
            'format' => 'json',
            'auth' => 'auth-token'))->
          assert_write($r, array('id' => 'test', 'user_id' => '123456789', 'group_id' => '@all', 'page' => 1, 'app_id' => 'application'));
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


/// <class name="Test.Service.OpenSocial..TestResource" extends="Service.OpenSocial.Resource">
class Test_Service_OpenSocial__TestResource extends Service_OpenSocial_Resource {

///   <protocol name="supporting">

///   <method name="set_updated" access="protected">
///     <args>
///       <arg name="d" type="Time.DateTime" />
///     </args>
///     <body>
  protected function set_updated(Time_DateTime $d) { $this['updated'] = $d->as_rfc1123(); }
///     </body>
///   </method>

///   <method name="get_updated" returns="Time.DateTime" access="protected">
///     <body>
  protected function get_updated() { return Time::DateTime($this['updated']); }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.OpenSocial.ResourceTest" extends="Dev.Unit.TestCase">
class Test_Service_OpenSocial_ResourceTest extends Dev_Unit_TestCase {

///   <protocol name="testing">

///   <method name="test_all">
///     <body>
  public function test_all() {
    $r = Core::with(
      new Test_Service_OpenSocial__TestResource(array(
        'id' => '123456789',
        'name' => 'John Doe',
        'profileUrl' => 'http://profile.myserver.com',
        'gender' => 'male')))->
        displayName('John');

    $this->
      asserts->
        accessing->
          assert_write($r, array(
            'profileSong' => 'song',
            'profile_video' => 'video',
            'updated' => Time::Datetime('2010-08-01 12:00')));

    $this->
      assert(is_null($r->missing));
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>

/// </module>
?>
