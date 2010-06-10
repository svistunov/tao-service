<?php
/// <module name="Service.Yandex.Direct.Manager" maintainer="timokhin@techart.ru" version="0.1.0">
Core::load('CLI.Application', 'IO.FS', 'Service.Yandex.Direct');

/// <class name="Service.Yandex.Direct.Manager" stereotype="module">
///   <implements interface="Core.ModuleInterface" />
///   <implements interface="CLI.RunInterface" />
class Service_Yandex_Direct_Manager implements Core_ModuleInterface, CLI_RunInterface {

///   <constants>
  const VERSION = '0.2.0';
///   </constants>

///   <protocol name="performing">

///   <method name="main" scope="class" returns="int">
///     <body>
  static public function main(array $argv) {
    return Core::with(new Service_Yandex_Direct_Manager_Application())->main($argv);
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


/// <class name="Service.Yandex.Direct.Manager.Exception" extends="Core.Exception">
class Service_Yandex_Direct_Manager_Exception extends Core_Exception {}
/// </class>

/// <class name="Service.Yandex.Direct.Manager.MissingCertificateException" extends="Service.Yandex.Direct.Manager.Exception">
class Service_Yandex_Direct_Manager_MissingCertificateException extends Service_Yandex_Direct_Manager_Exception {
  protected $path;

///   <protocol name="creating">

///   <method name="__construct">
///     <args>
///       <arg name="path" type="string" default="''" />
///     </args>
///     <body>
  public function __construct($path = '') {
    $this->path = $path;
    parent::__construct($path === '' ? 'Missing certificate' : "Missing certificate: $path");
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


class Service_Yandex_Direct_Manager_MissingTaskFileException extends Service_Yandex_Direct_Manager_Exception {

  protected $path;

///   <protocol name="creating">

///   <method name="__construct">
///     <args>
///       <arg name="path" type="string" />
///     </args>
///     <body>
  public function __construct($path) {
    $this->path = $path;
    parent::__construct("Missing task file: $path");
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>


/// <class name="Service.Yandex.Direct.Manager.Task" stereotype="abstract">
abstract class Service_Yandex_Direct_Manager_Task {

  protected $file;
  protected $name;
  protected $options;

///   <protocol name="creating">

///   <method name="__construct">
///     <args>
///       <arg name="file" type="IO.FS.File" />
///       <arg name="options" type="array" default="array" />
///     </args>
///     <body>
  public function __construct(IO_FS_File $file, array $options = array()) {
    $this->file = $file;
    $this->name = IO_FS::Path($file->path)->filename;
    $this->options = $options;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="performing">

///   <method name="run" returns="int">
///     <body>
  public function run() {
    $api = Service_Yandex_Direct::api();

    $campaigns = (isset($this->options['preload']) && $this->options['preload']) ?
      ((isset($this->options['direct']) && $this->options['direct']) ?
        $api->all_campaigns() : $api->campaigns_for($this->name)) :
      new Service_Yandex_Direct_CampaignsCollection(array());

      //ob_start();
    include($this->file->path);
    //ob_end_clean();
  }
///     </body>
///   </method>

///   <method name="stay_special" returns="Service.Yandex.Direct.Manager.UserTask" access="protected">
///     <args>
///       <arg name="limit" type="float" />
///       <arg name="phrases" />
///       <arg name="delta" type="float" default="0" />
///     </args>
///     <body>
  protected function stay_special($limit, $phrases, $delta = 0) {
    $phrases = $this->get_phrases_for($phrases);
    $prices = $phrases->prices;

    foreach ($phrases as $phrase)
      $prices->by_id($phrase->id)->price =
        $phrase->premium_min < $limit ?
          (($phrase->premium_min + $delta < $phrase->premium_max) ?  $phrase->premium_min + $delta : $phrase->premium_min + 0.01) : $limit;

    $prices->update();

    return $this;
  }
///     </body>
///   </method>

///   <method name="stay_visible" returns="Service.Yandex.Direct.Manager.UserTask" access="protected">
///     <args>
///       <arg name="limit" type="float" />
///       <arg name="phrases" />
///       <arg name="delta" type="float" default="0" />
///     </args>
///     <body>
  protected function stay_visible($limit, $phrases, $delta = 0) {
    $phrases = $this->get_phrases_for($phrases);
    $prices  = $phrases->prices;

    foreach ($phrases as $phrase)
      $prices->by_id($phrase->id)->price =
        $phrase->current_price < $phrase->min_price ?
          ($phrase->min_price + $delta < $limit ? $phrase->min_price + $delta : $limit) : $phrase->current_price;

    $prices->update();

    return $this;
  }
///     </body>
///   </method>

///   <method name="try_special" returns="Service.Yandex.Direct.Manager.UserTask" access="protected">
///     <args>
///       <arg name="limit" type="float" />
///       <arg name="phrases" />
///       <arg name="delta" type="float" default="0" />
///     </args>
///     <body>
  protected function try_special($limit, $phrases, $delta = 0) {
    $phrases = $this->get_phrases_for($phrases);
    $prices = $phrases->prices;

    foreach ($phrases as $phrase)
      $prices->by_id($phrase->id)->price =
        ($phrase->premium_min + $delta < $limit) ?
          ($phrase->premium_min + $delta) :
          ($phrase->current_price < $phrase->min_price ?
            ($phrase->min_price + $delta < $limit ?
              $phrase->min_price + $delta : $limit) :
              ($phrase->current_price > $limit ? $limit : $phrase->current_price));

    $prices->update();

    return $this;
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="supporting">

///   <method name="get_phrases_for" returns="Service.Yandex.Direct.PhrasesCollection" access="private">
///     <args>
///       <arg name="phrases" />
///     </args>
///     <body>
  private function get_phrases_for($phrases) {
    switch (true) {
      case $phrases instanceof Service_Yandex_Direct_Campaign:
      case $phrases instanceof Service_Yandex_Direct_CampaignsCollection:
        return $phrases->all_banners()->all_phrases();
      case $phrases instanceof Service_Yandex_Direct_Banner:
      case $phrases instanceof Service_Yandex_Direct_BannersCollection:
        return $phrases->all_phrases();
      case $phrases instanceof Service_Yandex_Direct_PhrasesCollection:
        return $phrases;
      default:
        throw new Service_Yandex_Direct_Manager_BadArgument('stay_special->phrases', $phrases);
    }
    return $phrases->where(array('LowCTR not', 1));
  }
///     </body>
///   </method>

///   </protocol>
}
/// </class>


/// <class name="Service.Yandex.Direct.Manager.UserTask" extends="Service.Yandex.Direct.Manager.Task">
class Service_Yandex_Direct_Manager_UserTask extends Service_Yandex_Direct_Manager_Task {}
/// </class>


/// <class name="Service.Yandex.Direct.Manager.Application" extends="CLI.Application.AbstractApplication">
class Service_Yandex_Direct_Manager_Application extends CLI_Application_AbstractApplication {

  protected $processed = 0;

///   <protocol name="performing">

///   <method name="run" returns="int">
///     <args>
///       <arg name="argv" type="array" />
///     </args>
///     <body>
  public function run(array $argv) {
    $this->
      check_certificate()->
      setup_api();

    return $this->options['run_all'] ? $this->run_all() : $this->run_tasks($argv);
  }
///     </body>
///   </method>

///   </protocol>

///   <protocol name="supporting">

///   <method name="check_certificate" returns="Service.Yandex.Direct.Manager access="private">
///     <body>
  private function check_certificate() {
    if (!isset($this->options['cert'])) throw new Service_Yandex_Direct_Manager_MissingCertificateException();
    if (!IO_FS::exists($p = $this->options['cert'])) throw new Service_Yandx_Direct_Manager_MissingCertificate($p);
    return $this;
  }
///     </body>
///   </method>

///   <method name="configure_proxy" returns="array" access="private">
///     <body>
  private function configure_proxy() {
    $res = array();
    if (($proxy =  (isset($this->options['proxy']) ?
           $this->options['proxy'] :
           ( ($p = getenv('http_proxy')) ? $p : ''))) &&
        ($m = Core_Regexps::match_with_results('{(?:https?://)?([^:]+):(?:(\d+))}', $proxy))) {
      if (isset($m[1])) $res['proxy_host'] = $m[1];
      if (isset($m[2])) $res['proxy_port'] = $m[2];
    }
    return $res;
  }
///     </body>
///   </method>

///   <method name="setup_api" returns="Service.Yandex.Direct.Manager" access="private">
///     <body>
  private function setup_api() {
    Service_Yandex_Direct::connect(
      array('local_cert' => $this->options['cert']) + $this->configure_proxy());
    return $this;
  }
///     </body>
///   </method>

///   <method name="run_all" returns="Service.Yandex.Direct.Manager" access="private">
///     <body>
  private function run_all() {
    foreach (IO_FS::Dir($this->options['prefix']) as $file) {
      Core::with(new Service_Yandex_Direct_Manager_UserTask(IO_FS::File($file), $this->options))->run();
      $this->processed++;
    }
  }
///     </body>
///   </method>

///   <method name="run_tasks" returns="Service.Yandex.Direct.Manager" access="private">
///     <args>
///       <arg name="tasks" type="array" />
///     </args>
///     <body>
  private function run_tasks(array $tasks) {
    foreach ($tasks as $name) {
      $path = $this->options['prefix'] ? $this->options['prefix'].$name.'.php' : $name;
      if (!IO_FS::exists($path)) throw new Service_Yandex_Direct_Manager_MissingTaskFileException($path);
      Core::with(new Service_Yandex_Direct_Manager_Task(IO_FS::File($path), $this->options))->run();
      $this->processed++;
    }
    return $this;
  }
///   </method>

///   <method name="setup" access="protected">
///     <body>
  protected function setup() {
    return parent::setup()->
      usage_text(Core_Strings::format(
        "Service.Yandex.Direct.Manager %s: Yandex.Direct campaigns manager\n",
          Service_Yandex_Direct_Manager::VERSION))->
      options(
        array(
          array('cert',    '-c', '--cert',    'string',  null, 'Client certificate'),
          array('proxy',   '-p', '--proxy',   'string',  null, 'HTTP proxy'),
          array('prefix',  '-i', '--prefix',  'string',  null, 'Tasks prefix'),
          array('preload', '-l', '--preload', 'boolean', true, 'Preload campaigns'),
          array('run_all', '-a', '--all',     'boolean', true, 'Run all tasks'),
          array('direct',  '-d', '--direct',  'boolean', true, 'Direct client, not agency')),
        array('certificate' => null,
              'proxy' => null,
              'prefix' => '',
              'preload' => true,
              'run_all' => false,
              'direct'  => false));
  }
///     </body>
///   </method>

///   </protocol>

}
/// </class>

/// </module>
?>