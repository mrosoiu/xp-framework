<?php

  /* This class is part of the XP framework
   *
   * $Id$
   */

  uses(
    'unittest.TestCase',
    'util.CompositeProperties'
  );

  /**
   * TestCase
   *
   * @see       ...
   * @purpose   TestCase for
   */
  class CompositePropertiesTest extends TestCase {

    /**
     * Test
     *
     */
    #[@test]
    public function createCompositeSingle() {
      $c= new CompositeProperties(new Properties(''));
      $this->assertEquals(1, $c->length());
    }

    /**
     * Test
     *
     */
    #[@test]
    public function createCompositeDual() {
      $c= new CompositeProperties(new Properties(''), array(new Properties('')));
      $this->assertEquals(2, $c->length());
    }

    protected function fixture() {
      return new CompositeProperties(Properties::fromString('[section]
str="string..."
b1=true'),
array(Properties::fromString('[section]
str="Another thing"
str2="Another thing"
b1=false
b2=false
')));

      return $c;
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readStringUsesFirstProperties() {
      $this->assertEquals('string...', $this->fixture()->readString('section', 'str'));
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readStringUsesSecondPropertiesWhenFirstEmpty() {
      $this->assertEquals('Another thing', $this->fixture()->readString('section', 'str2'));
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readStringReturnsDefaultOnNoOccurrance() {
      $this->assertEquals('Hello World', $this->fixture()->readString('section', 'str3', 'Hello World'));
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readBooleanUsesFirst() {
      $this->assertEquals(TRUE, $this->fixture()->readBool('section', 'b1'));
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readBooleanUsesSecondIfFirstUnset() {
      $this->assertEquals(FALSE, $this->fixture()->readBool('section', 'b2'));
    }

    /**
     * Test
     *
     */
    #[@test]
    public function readBooleanUsesDefaultOnNoOccurrance() {
      $this->assertEquals('Hello.', $this->fixture()->readBool('section', 'b3', 'Hello.'));
    }
  }
?>
