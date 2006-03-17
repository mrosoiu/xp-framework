<?php
  package io {
    interface Serializable { 
      const SERIALVERSIONUID= -32423020;
      
      function getId();
    }
  }

  package bar {
    import io~Serializable;
    
    enum Coin {
      penny(1), nickel(5), dime(10), quarter(25);
    }
    
    class Legacy {
      var $old;
    }

    class Foo extends xp~lang~Object implements Serializable {
      public $id= 0;
      protected $foo= -1;
      public static $instance= NULL;

      protected __construct() { }

      public static function getInstance() {
        if (!isset(self::$instance)) {
          self::$instance= new self();
        }

        return self::$instance;
      }

      [@webmethod, @deprecated('use id() instead'), @security(role= 'user')]
      public function getId() {
        return $this->id;
      }
      
      public static operator + () throws xp~lang~Exception {
        throw new Exception('Not yet implemented');
      }
    }
  }
  
  try {
    $id= bar~Foo::getInstance()->getId();
    throw new Exception();
  } catch (Exception $e) {
    $e->printStackTrace();
  }
  
  echo $id;
?>
