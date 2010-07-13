<?php
/// <module name="Test.Service.Google.Chart" version="0.1.0" maintainer="0.1.0@techart.ru">
Core::load('Dev.Unit', 'Service.Google.Chart');

/// <class name="Test.Service.Google.Chart" stereotype="module">
///   <implements interface="Dev.Unit.TestModuleInterface" />
class Test_Service_Google_Chart implements Dev_Unit_TestModuleInterface {

///   <constants>
  const VERSION = '0.1.0';
///   </constants>

///   <protocol name="testing">

///   <method name="suite" returns="Dev.Unit.TestSuite" scope="class">
///     <body>
  static public function suite() {
    return Dev_Unit::load_with_prefix('Test.Service.Google.Chart.', 'EncodeCase', 'GraphCase');
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.Chart.EncodeCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_Chart_EncodeCase extends Dev_Unit_TestCase {
  protected $data;
  protected $range;
///   <protocol name="testing">

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
    $this->data = array(
      array(40, 25, 10, 65, 15, 25, 35, 15, 5, 0, 35, 70, 15, 23, 11, 60),
      array(
        array(-10, 20, 30, 40, 50, 60, 70, 80, 90,100, 110, 120, 130, 140, 150, 160),
        array(10, 20, 30, 40, 50, 60, 10, 10, 10, 10, 10, 74, 10, 10, 10, 0)
      )
    );
    $this->range = (object) array(
      'left' => -10,
      'right' => 160,
      'bottom' => 0,
      'top' => 74
    );
  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
 public function test_accessing() {
    $encoder = new Service_Google_Chart_SimpleEncoder('pref', ';');
    $this->asserts->accessing->
      assert_read_only($encoder, $ro = array(
        'prefix' => 'pref',
        'separator' => ';'
      ))->
      assert_undestroyable($encoder, $ro)->
      assert_missing($encoder);
  }
///     </body>
///   </method>

///   <method name="test_simple">
///     <body>
  public function test_simple() {
    $encoder = new Service_Google_Chart_SimpleEncoder();
    $this->
      assert_equal(
        $encoder->encode_all($this->data, $this->range),
        's:gUI1MUcMEAc5MSJx,AKORVZcgjnruy159,IQYgpxIIIII9IIIA'
      );
  }
///     </body>
///   </method>

///   <method name="test_text">
///     <body>
  public function test_text() {
    $encoder = new Service_Google_Chart_TextEncoder();
    $this->
      assert_equal(
        $encoder->encode_all($this->data, $this->range),
        't:54.1,33.8,13.5,87.8,20.3,33.8,47.3,20.3,6.8,0,47.3,94.6,20.3,31.1,14.9,81.1|0,17.6,23.5,29.4,35.3,41.2,47.1,52.9,58.8,64.7,70.6,76.5,82.4,88.2,94.1,100|13.5,27,40.5,54.1,67.6,81.1,13.5,13.5,13.5,13.5,13.5,100,13.5,13.5,13.5,0'
      );
  }
///     </body>
///   </method>

///   <method name="test_text">
///     <body>
  public function test_extended() {
    $encoder = new Service_Google_Chart_ExtendedEncoder();
    $this->
      assert_equal(
        $encoder->encode_all($this->data, $this->range),
        'e:ilVnIp4MM-VneQM-EU__eQ8hM-T4Jgz4,AALSPDS0WlaWeHh3lopZtKw70s4d8O..,IpRSZ8ilrOz4IpIpIpIpIp..IpIpIp__'
      );
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>

/// <class name="Test.Service.Google.Chart.GraphCase" extends="Dev.Unit.TestCase">
class Test_Service_Google_Chart_GraphCase extends Dev_Unit_TestCase {
  protected $graph;
///   <protocol name="testing">

///   <method name="setup">
///     <body>
  protected function setup() {
    $this->graph = Service_Google_Chart::linexy('300x120');
  }
///     </body>
///   </method>

///   <method name="test_indexing">
///     <body>
  public function test_indexing() {
    $this->asserts->indexing->
      assert_read($this->graph, $r = array(
        'chs' => '300x120'
      ))->
      assert_write($this->graph, $w = array(
        'name' => 'value'
      ))->
      assert_nullable($this->graph, $w);

  }
///     </body>
///   </method>

///   <method name="test_accessing">
///     <body>
  public function test_accessing() {
    $this->asserts->accessing->
      assert_read_only($this->graph, array(
        'type' => 'lxy',
        'data' => array(),
        'opts' => $opts =  array('chs' => '300x120'),
        'range' => (object) array('left' => null, 'right' => null, 'top' => null, 'bottom' => null),
        'request' => Net_HTTP::Request(Service_Google_Chart::SERVER_URL)->parameters($opts)
      ))->
      assert_exists_only($this->graph, array('encoder'));
  }
///     </body>
///   </method>

///   <method name="test_encoders">
///     <body>
  public function test_encoders() {
    $this->graph->simple();
    $this->assert_class('Service_Google_Chart_SimpleEncoder', $this->graph->encoder);

    $this->graph->text();
    $this->assert_class('Service_Google_Chart_TextEncoder', $this->graph->encoder);

    $this->graph->extended();
    $this->assert_class('Service_Google_Chart_ExtendedEncoder', $this->graph->encoder);
  }
///     </body>
///   </method>

///   <method name="test_data">
///     <body>
  public function test_data() {
    $this->graph->data(10, 20, 30, -10);
    $this->
      assert_equal(
        $this->graph->data,
        array(array(10, 20, 30, -10)))->
      assert_equal(
        $this->graph->range,
        (object) array('left' => NULL, 'right' => NULL, 'top' => 30, 'bottom' => -10));
  }
///     </body>
///   </method>

///   <method name="test_data_from">
///     <body>
  public function test_data_from() {
    $data = Core::hash(array(
        Core::object(array('count' => 10)),
        Core::object(array('count' => -10)),
        Core::object(array('count' => -20)),
        Core::object(array('count' => 15))));


    $this->graph->data_from($data, 'count', true);
    $this->
      assert_equal(
        $this->graph->data,
        $d = array(array(10, -10, -20, 15))
      )->
      assert_equal(
        $this->graph->range,
        $r = (object) array('left' => null, 'right' => null, 'top' => 15, 'bottom' => -20)
      );

    $data = array(10, -10, -20, 15);
    $this->graph->reset()->data_from($data);
    $this->
      assert_equal($this->graph->data, $d)->
      assert_equal($this->graph->range, $r);
  }
///     </body>
///   </method>

///   <method name="test_data_xy">
///     <body>
  public function test_data_xty() {
    $this->graph->data_xy(array(1 => 10, 2 => -5, 3 => 20));
    $this->
      assert_equal(
        $this->graph->data,
        $d = array(array(array(1,2,3), array(10,-5,20))))->
      assert_equal(
        $this->graph->range,
        $r = (object) array('left' => 1, 'right' => 3, 'top' => 20, 'bottom' => -5)
      );

    $this->graph->reset()->data_xy(array(1, 2, 3), array(10, -5, 20));
    $this->
      assert_equal($this->graph->data, $d)->
      assert_equal($this->graph->range, $r);
  }
///     </body>
///   </method>

///   <method name="test_data_xy_from">
///     <body>
  public function test_data_xy_from() {
    $data = Core::hash(array(
      (object) array('time' => 1, 'count' => 10),
      (object) array('time' => 3, 'count' => 40),
      (object) array('time' => 10, 'count' => 0)
    ));
    $this->graph->data_xy_from($data, 'count', 'time', true);
    $this->
      assert_equal(
        $this->graph->data,
        $d = array(array(array(1,3,10), array(10,40,0)))
      )->
      assert_equal(
        $this->graph->range,
        $r = (object) array('left' => 1, 'right' => 10, 'top' => 40, 'bottom' => 0)
      );

    $data = array(
      1 => array('count' => 10),
      3 => array('count' => 40),
      10 => array('count' => 0)
    );
    $this->graph->reset()->data_xy_from($data, 'count');
    $this->
      assert_equal($this->graph->data, $d)->
      assert_equal($this->graph->range, $r);

    $data = array(1 => 10, 3 => 40, 10 => 0);
    $this->graph->reset()->data_xy_from($data);
    $this->
      assert_equal($this->graph->data, $d)->
      assert_equal($this->graph->range, $r);
  }
///     </body>
///   </method>

///   <method name="test_configure">
///     <body>
  public function test_configure() {
    $this->graph->
      size('400x230')->
      title('The title')->
      legend('legend1', 'legend2');
    $this->assert_equal(
      $this->graph->as_array(),
      $ar = array(
      'chs' => '400x230',
      'chtt' => 'The+title',
      'chdl' => 'legend1|legend2',
      'cht' => 'lxy',
      'chd' => 's:')
    );

    $this->graph->
      legend_from(array(array('legend' => 'legend3')), 'legend')->
      colors('FFCC33', 'FF5C11', '1FCC00')->
      bottom(20)->
      top(100)->
      left(-10)->
      right(10)->
      marker('c', 'FF0000', 0, -1, 5)->
      marker('r', '00FF00', 0, 0.2, .7)->
      grid(10,10)->
      line_style(3, 2, 1)->
      line_style(1, 1, 2);

    $this->
      assert_equal(
        $this->graph->as_array(),
        array_merge($ar, array(
          'chdl' => 'legend3',
          'chco' => 'FFCC33,FF5C11,1FCC00',
          'chm' => 'c,FF0000,0,-1,5|r,00FF00,0,0.2,0.7',
          'chg' => '10,10',
          'chls' => '3,2,1|1,1,2'
        )))->
      assert_equal(
        $this->graph->range,
        (object) array('left' => -10, 'right' => 10, 'top' => 100, 'bottom' => 20)
      );

    $this->set_trap();
    try{
      $this->graph->labels('label1', 'label2');
    } catch(Service_Google_Chart_UnsupportedMethodException $e) {
      $this->trap($e);
    }
    $this->assert_true($this->is_catch_prey());

    $graph = Service_Google_Chart::pie_3d('300x150');
    $graph->
      labels('label 1', 'label 2')->
      orientation(0.7);
    $this->assert_equal(
      $graph->as_array(),
      $ar = array(
        'chs' => '300x150',
        'chl' => 'label+1|label+2',
        'chp' => 0.7,
        'cht' => 'p3',
        'chd' => 's:'
      )
    );
    $graph->labels_from(array(array('label' => 'label 3')), 'label');
    $this->assert_equal(
      $graph->as_array(),
      array_merge($ar, array('chl' => 'label+3'))
    );

    $this->
      assert_equal(
        Service_Google_Chart::bar_horizontal_group('100x100')->
          spacing(12,1,2)->zero_line(0.2,0.3)->as_array(),
          array(
            'chs' => '100x100',
            'chbh' => '12,1,2',
            'chp' => '0.2,0.3',
            'cht' => 'bhg',
            'chd' => 's:'
          )
      )->
      assert_equal(
        Service_Google_Chart::scatter('300x200')->
          data_xy(array(10 => 5, 15=> 40, 30 => 80))->sizes(50, 70, 100)->as_array(),
        array(
          'chs' => '300x200',
          'cht' => 's',
          'chd' => 's:AP9,Ac9,eq9'
        )
      )->
      assert_equal(
        Service_Google_Chart::map('400x200')->area('world')->
          colors('FFFFFF', 'FF0000', '00FF00')->
          data(0,100)->
          countries('RU', 'UA')->
          as_array(),
        array(
          'chs' => '400x200',
          'chtm' => 'world',
          'chco' => 'FFFFFF,FF0000,00FF00',
          'chld' => 'RUUA',
          'cht' => 't',
          'chd' => 's:A9'
        )
      );

  }
///     </body>
///   </method>

///   <method name="test_stringifying">
///     <body>
  public function test_stringifying() {
    $this->graph->data(10, 100, 97);
    $this->asserts->stringifying->
      assert_string($this->graph, 'http://chart.apis.google.com/chart?chs=300x120&cht=lxy&chd=s%3AA96');
  }
///     </body>
///   </method>

///   <method name="test_axis">
///     <body>
  public function test_axis() {
    $this->graph->
      data_xy(array(-10 => 10, 20 => 20, 30 => 35,40 => 5))->
      axis('x')->
        auto_range(10)->
        tick(3)->
        plabels(array(10 => 'label 1', 20 => 'label 3', 40 => 'label 4'))->
        style('0000DD', 13, -1, 't', 'FF0000')->
      end->
      axis('y')->
        range(5, 35, 5)->
        tick(5)->
        labels('label 1', 'label 2')->
        positions(20, 30)->
        style('0000DD', 13, 0, 't');
    $this->assert_equal(
      $this->graph->as_array(),
      array(
        'chs' => '300x120',
        'chxt' => 'x,y',
        'chxr' => '0,-10,40,10|1,5,35,5',
        'chxtc' => '0,3|1,5',
        'chxl' => '0:|label+1|label+3|label+4|1:|label+1|label+2',
        'chxp' => '0,10,20,40|1,20,30',
        'chxs' => '0,0000DD,13,-1,t,FF0000|1,0000DD,13,0,t',
        'cht' => 'lxy',
        'chd' => 's:Akw9,Ke9A'
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