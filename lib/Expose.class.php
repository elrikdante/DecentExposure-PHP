<?php
  class Expose
  {
    static public $exposes = array();

    static public function add_exposure($name, $func){
      self::$exposes[$name] = $func ? $func : self::guess_exposure_type($name);
      return true;
    }

    static public function run_exposure($name){
      if(self::expose_exists($name)){
        return self::$exposes[$name];
      }
      else {
        self::$exposes[$name] = call_user_func(self::$exposes[$name]);
        return self::$exposes[$name];
      }
    }

    static public function expose_exists($name){
      return !(is_callable(self::$exposes[$name]));
    }

    static public function guess_exposure_type($name){
      $class = classify($name);
      if (class_exists($class)){
        $instance_attribs = array() ;
        $nested_param = isset($_REQUEST[$name]) and is_array($_REQUEST[$name]);
        if($nested_param){
            foreach(array_keys($_REQUEST[$name]) as $attrib){
              if(!is_numeric($attrib)){
                $instance_attribs[$attrib] = $_REQUEST[$name][$attrib];
              }
            }
        }
        if(sizeof($instance_attribs) < 1){
          $instance_attribs = null;
        }
        return new $class($instance_attribs);
      }
      if (isset($_REQUEST[$name])){
       return $_REQUEST[$_name];
      };
      return null;
    }
  };

  function expose($name, $func=null){
    Expose::add_exposure($name, $func);
    eval("global $$name;");
    eval("$$name = Expose::run_exposure('$name');");
  };
?>
