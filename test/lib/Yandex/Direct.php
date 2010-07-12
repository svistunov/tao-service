<?php
/// <module name="Test.Service.Yandex.Direct" version="0.1.0" maintainer="svistunov@techart.ru">
Core::load('Dev.Unit', 'Service.Yandex.Direct');

/// <class name="Test.Service.Yandex.Direct" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_Yandex_Direct implements Dev_Unit_TestModuleInterface {

///   <constants>
  const VERSION = '0.1.0';
///   </constants>

///   <protocol name="testing">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.Yandex.Direct.',
     'EntityCase',
      'CollectionCase',
      'IndexedCollectionCase',
      'MapperCase',
      'FilterCase'
    );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.Entity" extends="Service.Yandex.Direct.Entity" >
///   <implements interface="Core.EqualityInterface" />
class Test_Service_Yandex_Direct_Entity extends Service_Yandex_Direct_Entity
  implements Core_EqualityInterface {
  public $called_methods = array();
  protected $_property = 'property value';
  protected $_assigned;
///   <protocol name="accessing">

///   <method name="get_cached">
///     <body>
  protected function get_simple() {
    $this->called_methods[] = array('get_simple' => array());
    return 'simple value';
  }
///     </body>
///   </method>

///   <method name="get_property">
///     <body>
  protected function get_property() {
    $this->called_methods[] = array('get_property' => array());
    return $this->_property;
  }
///     </body>
///   </method>

///   <method name="set_property">
///     <args>
///       <arg name="value" type="string" />
///     </args>
///     <body>
  protected function set_property($value) {
    $this->called_methods[] = array('set_property' => array($value));
    return $this->_property = (string) $value;
  }
///     </body>
///   </method>

///   <method name="isset_property">
///     <body>
  protected function isset_property() {
    $this->called_methods[] = array('isset_property' => array());
    return (boolean) $this->_property;
  }
///     </body>
///   </method>

///   <method name="set_assigned">
///     <args>
///       <arg name="value" type="" />
///     </args>
///     <body>
  protected function set_assigned($value) {
    $this->called_methods[] = array('set_assigned' => array($value));
    $this->_assigned = $value;
  }
///     </body>
///   </method>

///   <method name="get_assigned">
///     <body>
  protected function get_assigned() {
    $this->called_methods[] = array('get_assigned' => array());
    return $this->_assigned;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="quering">
///   <method name="equals" returns="boolean">
///     <args>
///       <arg name="to" />
///     </args>
///     <body>
  public function equals($to) {
    return ($to instanceof self) &&
      Core::equals($this->entity, $to->__entity);
  }
///     </body>
///   </method>
///</protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.EntityCase" extends="Dev.Unit.TestCase">
class Test_Service_Yandex_Direct_EntityCase extends Dev_Unit_TestCase {
    protected $entity;
    protected $_entity;
///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
    $this->entity = new Test_Service_Yandex_Direct_Entity(
      $this->_entity = Core::object(
        array('CamelCase' => 'value')
      )
    );
    $this->entity->assign(array('assigned' => 'assign value'));
  }
///     </body>
///   </method>

///   <method name="test_accessign">
///     <body>
 public function test_accessing() {
    $this->asserts->accessing->
      assert_read_only($this->entity, array(
        '__entity' => $this->_entity,
        'camel_case' => 'value',
        'simple' => 'simple value'
      ))->
      assert_read($this->entity, array(
        'property' => 'property value',
        'assigned' => 'assign value',
      ))->
      assert_write($this->entity, array(
        'property' => ''
      ))->
      assert_true(isset($this->entity->simple))->
      assert_false(isset($this->entity->property))->
      assert_equal($this->entity->called_methods, array(
        array('set_assigned' => array('assign value')),
        array('get_simple' => array()),
        array('isset_property' => array()),
        array('get_property' => array()),
        array('get_assigned' => array()),
        array('set_property' => array('')),
        array('get_property' => array()),
        array('isset_property' => array())

      ));
  }
///     </body>
///   </method>

///   <method name="test_indexing">
///     <body>
 public function test_indexing() {
    $this->asserts->indexing->
      assert_read_only($this->entity, array(
        'CamelCase' => 'value',
      ));
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.Collection" extends="Service.Yandex.Direct.Collection">
class Test_Service_Yandex_Direct_Collection extends Service_Yandex_Direct_Collection {
  public $called_methods = array();

  protected $cached;
  protected $_property = 'property value';

///   <protocol name="accessing">

///   <method name="get_cached">
///     <body>
  public function get_cached() {
    $this->called_methods[] = array('get_cached' => array());
    return 'cache me';
  }
///     </body>
///   </method>

///   <method name="get_property">
///     <body>
  protected function get_property() {
    $this->called_methods[] = array('get_property' => array());
    return $this->_property;
  }
///     </body>
///   </method>

///   <method name="isset_property">
///     <body>
  protected function isset_property() {
    $this->called_methods[] = array('isset_property' => array());
    return isset($this->_property);
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="supporting">

///   <method name="wrap" returns="object">
///     <args>
///       <arg name="entity" type="object" />
///     </args>
///     <body>
  protected function wrap($entity) {
    $this->called_methods[] = array('wrap' => array($entity));
    return new Test_Service_Yandex_Direct_Entity($entity);
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


/// <class name="Test.Service.Yandex.Direct.CollectionCase" extends="Dev.Unit.TestCase">
class Test_Service_Yandex_Direct_CollectionCase extends Dev_Unit_TestCase {
    protected $collection;
    protected $_items;

///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
    $this->collection = new Test_Service_Yandex_Direct_Collection(
      $this->_items = array(
        Core::object(array('Int' => 10,  'String' => 'message')),
        Core::object(array('Int' => 5,   'String' => 'essa')),
        Core::object(array('Int' => 24,  'String' => 'egassem')),
        Core::object(array('Int' => 1,   'String' => 'test')),
        Core::object(array('Int' => 100, 'String' => 'test123')),
        Core::object(array('Int' => 102, 'String' => ''))
    ));
  }
///     </body>
///   </method>

///   <method name="test_accessign">
///     <body>
 public function test_accessing() {
    $this->asserts->accessing->
      assert_read_only($this->collection, array(
        '__items' => $this->_items,
        'cached' => 'cache me',
        'property' => 'property value'
      ))->
      assert_true(isset($this->collection->cached))->
      assert_true(isset($this->collection->property))->
      //Проверяем кеширование
      assert_equal($this->collection->cached, 'cache me')->
      assert_equal($this->collection->called_methods, array(
        array('get_cached' => array()),
        array('isset_property' => array()),
        array('get_property' => array()),
        array('isset_property' => array())
      ));
  }
///     </body>
///   </method>

///   <method name="test_indexing">
///     <body>
 public function test_indexing() {
    $this->asserts->indexing->
      assert_read_only($this->collection, array(
        0 => new Test_Service_Yandex_Direct_Entity($this->_items[0]),
        1 => new Test_Service_Yandex_Direct_Entity($this->_items[1])
      ))->
      assert_equal($this->collection->called_methods, array(
        array('wrap' => array($this->_items[0])),
        array('wrap' => array($this->_items[1]))
      ));
  }
///     </body>
///   </method>

///   <method name="test_performing">
///     <body>
  public function test_performing() {
    $this->collection->assign(array('property' => 'new value'));
    //TODO: и как это проверять и зачем это вообще нужно
//    $this->
//      assert_equal(
//        $this->collection->get(0)->property,
//        'new value'
//      );
  }
///     </body>
///   </method>

///   <method name="test_where">
///     <body>
  public function test_where() {
    $tests = array(
      array(
        'operation' => array('string ~' => '{essa}'),
        'res' => array(0, 1)
      ),
      array(
        'operation' => array('string ~' => '{essa}', 'int' => 10),
        'res' => array(0)
      ),
      array(
        'operation' => array('string ~' => '{test}', 'int in' => array(100, 101)),
        'res' => array(4)
      ),
      array(
        'operation' => array('int !in' => array(10, 5), 'int in' => array(10, 5, 24, 1)),
        'res' => array(2, 3)
      ),
      array(
        'operation' => array('string not' => '', 'int in' => array(102, 1, 5)),
        'res' => array(5)
      ),
    );

    foreach ($tests as $ind => $t) {
     $res = $this->collection->where($t['operation']);
     $this->
       assert_equal(
         $res->__items,
         $this->array_subset($this->_items, $t['res'])
       );
    }
  }
///     </body>
///   </method>

///   <method name="test_iterating">
///     <body>
  public function test_iterating() {
    $this->
      assert_equal(count($this->collection), 6)->
      asserts->iterating->
      assert_read($this->collection, array(
        0 => new Test_Service_Yandex_Direct_Entity($this->_items[0]),
        1 => new Test_Service_Yandex_Direct_Entity($this->_items[1]),
        2 => new Test_Service_Yandex_Direct_Entity($this->_items[2]),
        3 => new Test_Service_Yandex_Direct_Entity($this->_items[3]),
        4 => new Test_Service_Yandex_Direct_Entity($this->_items[4]),
        5 => new Test_Service_Yandex_Direct_Entity($this->_items[5])
      ));
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="support">

///   <method name="array_subset" returns="array" access="protected">
///     <args>
///       <arg name="source" type="array" />
///       <arg name="indexes" type="array" />
///     </args>
///     <body>
  protected function array_subset(array $source, array $indexes) {
    $res = array();
    foreach($indexes as $ind)
      $res[] = $source[$ind];
    return $res;
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.IndexedCollection" extends="Service.Yandex.Direct.IndexedCollection">
class Test_Service_Yandex_Direct_IndexedCollection extends Service_Yandex_Direct_IndexedCollection {

///   <protocol name="supporting">

///   <method name="entity_id_for" returns="int" access="protected">
///     <args>
///       <arg name="entity" type="object" />
///     </args>
///     <body>
  protected function entity_id_for($entity) {
    return $entity->id;
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>

/// <class name="Test.Service.Yandex.Direct.IndexedCollectionCase" extends="Dev.Unit.TestCase">
class Test_Service_Yandex_Direct_IndexedCollectionCase extends Dev_Unit_TestCase {

  protected $collection;
  protected $_items;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  protected function setup() {
    $this->collection = new Test_Service_Yandex_Direct_IndexedCollection(
      $this->_items = array(
        Core::object(array('id' => 1, 'title' => 'Title1')),
        Core::object(array('id' => 2, 'title' => 'Title2')),
        Core::object(array('id' => 3, 'title' => 'Title3')),
        Core::object(array('id' => 4, 'title' => 'Title4')),
      )
    );
  }
///     </body>
///   </method>

///   <method name="test_all">
///     <body>
  public function test_all() {
    $this->asserts->accessing->
      assert_read_only($this->collection, array(
        '__ids' => array(1, 2, 3, 4),
        '__items' => $this->_items
      ))->
      assert_equal(
        $this->collection->by_id(3),
        $this->_items[2]
      );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.Mapper" extends="Service.Yandex.Direct.Mapper">
class Test_Service_Yandex_Direct_Mapper extends Service_Yandex_Direct_Mapper {
  public $called_methods = array();

  protected $cached;
  protected $_property = 'property value';

///   <protocol name="accessing">

///   <method name="get_cached">
///     <body>
  public function get_cached() {
    $this->called_methods[] = array('get_cached' => array());
    return 'cache me';
  }
///     </body>
///   </method>

///   <method name="get_property">
///     <body>
  protected function get_property() {
    $this->called_methods[] = array('get_property' => array());
    return $this->_property;
  }
///     </body>
///   </method>

///   </protocol>
}
//  </class>

/// <class name="Test.Service.Yandex.Direct.MapperCase" extends="Test.Service.Yandex.Direct.MapperCase">
class Test_Service_Yandex_Direct_MapperCase extends Dev_Unit_TestCase {
  protected $mapper;

///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  public function setup() {
    $this->mapper = new Test_Service_Yandex_Direct_Mapper();
  }
///     </body>
///   </method>

///   <method name="test_all">
///     <body>
  public function test_all() {
    $this->asserts->accessing->
      assert_read_only($this->mapper, array(
        'cached' => 'cache me',
        'property' => 'property value'
      ))->
      assert_equal($this->mapper->cached, 'cache me')->
      assert_equal(
        $this->mapper->called_methods,
        array(
          array('get_cached' => array()),
          array('get_property' => array())
      ));
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.Filter" extends="Service.Yandex.Direct.Filter" >
class Test_Service_Yandex_Direct_Filter extends Service_Yandex_Direct_Filter {
  public $called_methods = array();

  protected $_property;

///   <protocol name="accessing">

///   <method name="set_property">
///     <args>
///       <arg name="value" type="mixed" />
///     </args>
///     <body>
  protected function set_property($value) {
    $this->called_methods[] = array('set_property' => array($value));
    return $this->_property = $value;
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Yandex.Direct.FilterCase" extends="Test.Service.Yandex.Direct.MapperCase">
class Test_Service_Yandex_Direct_FilterCase extends Dev_Unit_TestCase {

  protected $filter;

///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  public function setup() {
    $this->filter = new Test_Service_Yandex_Direct_Filter();
  }
///     </body>
///   </method>

///   <method name="test_all">
///     <body>
  public function test_all() {
    $this->filter->property('test value');
    $this->filter->camel_case('camel_case value');
    $this->
      assert_equal(
        $this->filter->called_methods,
        array(
          array('set_property' => array('test value'))
      ))->
      assert_equal(
        $this->filter->as_array(),
        array('CamelCase' => 'camel_case value')
      );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// </module>
?>