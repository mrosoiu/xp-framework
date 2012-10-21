<?php
/* This class is part of the XP framework
 *
 * $Id$
 */

  uses(
    'unittest.TestCase',
    'webservices.rest.server.routing.RestDefaultRouter',
    'scriptlet.HttpScriptletRequest',
    'scriptlet.HttpScriptletResponse'
  );
  
  /**
   * Test default router
   *
   * @see  xp://webservices.rest.server.routing.RestDefaultRouter
   */
  class RestDefaultRouterTest extends TestCase {
    protected $fixture= NULL;
    protected static $package= NULL;

    /**
     * Sets up fixture package
     *
     */
    #[@beforeClass]
    public static function fixturePackage() {
      self::$package= Package::forName('net.xp_framework.unittest.rest.fixture');
    }

    /**
     * Sets up router fixture
     *
     */
    public function setUp() {
      $this->fixture= new RestDefaultRouter();
      $this->fixture->configure(self::$package->getName());
    }

    /**
     * Returns a method from given fixture class
     *
     * @param  string class
     * @param  string method
     * @return lang.reflect.Method
     */
    protected function fixtureMethod($class, $method) {
      return self::$package->loadClass($class)->getMethod($method);
    }

    /**
     * Test routesFor()
     * 
     */
    #[@test]
    public function greet_default() {
      $this->assertEquals(
        array(array(
          'target'   => $this->fixtureMethod('GreetingHandler', 'greet'),
          'segments' => array(0 => '/greet/test', 'name' => 'test', 1 => 'test'),
          'input'    => NULL,
          'output'   => 'text/json'
        )),
        $this->fixture->routesFor('GET', '/greet/test', NULL, new Preference('*/*'), array('text/json'))
      );
    }

    /**
     * Test routesFor()
     * 
     */
    #[@test]
    public function greet_custom() {
      $this->assertEquals(
        array(array(
          'target'   => $this->fixtureMethod('GreetingHandler', 'hello'),
          'segments' => array(0 => '/hello/test', 'name' => 'test', 1 => 'test'),
          'input'    => NULL,
          'output'   => 'application/vnd.example.v2+json'
        )),
        $this->fixture->routesFor('GET', '/hello/test', NULL, new Preference('application/vnd.example.v2+json'), array('text/json'))
      );
    }

    /**
     * Test routesFor()
     *
     */
    #[@test]
    public function greet_post() {
      $this->assertEquals(
        array(array(
          'target'   => $this->fixtureMethod('GreetingHandler', 'greet_posted'),
          'segments' => array(0 => '/greet'),
          'input'    => 'application/json',
          'output'   => 'text/json'
        )),
        $this->fixture->routesFor('POST', '/greet', 'application/json', new Preference('*/*'), array('text/json'))
      );
    }

    /**
     * Test routesFor()
     *
     */
    #[@test]
    public function hello_post() {
      $this->assertEquals(
        array(
          array(
            'target'   => $this->fixtureMethod('GreetingHandler', 'hello_posted'),
            'segments' => array(0 => '/greet'),
            'input'    => 'application/vnd.example.v2+json',
            'output'   => 'text/json'
          ),
          array(
            'target'   => $this->fixtureMethod('GreetingHandler', 'greet_posted'),
            'segments' => array(0 => '/greet'),
            'input'    => 'application/vnd.example.v2+json',    // because it accepts "*/*"
            'output'   => 'text/json'
          )
        ),
        $this->fixture->routesFor('POST', '/greet', 'application/vnd.example.v2+json', new Preference('*/*'), array('text/json'))
      );
    }

    /**
     * Test routesFor()
     *
     */
    #[@test]
    public function no_say_route() {
      $this->assertEquals(
        array(),
        $this->fixture->routesFor('GET', '/say', NULL, new Preference(''))
      );
    }

    /**
     * Test routesFor()
     *
     */
    #[@test]
    public function no_slash_route() {
      $this->assertEquals(
        array(),
        $this->fixture->routesFor('GET', '/', NULL, new Preference(''))
      );
    }
  }
?>
