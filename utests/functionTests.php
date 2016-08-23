<?php

/**
* https://blogs.kent.ac.uk/webdev/2011/07/14/phpunit-and-unserialized-pdo-instances/
* @backupGlobals disabled
*/

class dahdiFunctionsTest extends PHPUnit_Framework_TestCase{

	//Will be FreePBX BMO object

		protected static $f;

		//Will become your Class object

		protected static $o;

		//Module name used in test output as self::$module. Can be anything unless you want to use this as something more.

		protected static $module = 'Dahdiconfig';

		//Change Moduleclass to your class name

		public static function setUpBeforeClass() {

				include 'setuptests.php';

				self::$f = FreePBX::create();

				self::$o = self::$f->Dahdiconfig;

		}

		//Stuff before the test

		public function setup() {}

		//Leave this alone, it test that PHPUnit is working

		public function testPHPUnit() {

				$this->assertEquals("test", "test", "PHPUnit is broken.");

				$this->assertNotEquals("test", "nottest", "PHPUnit is broken.");

		}

		//This tests that the the BMO object for your class is an object

		public function testCreate() {;

				$this->assertTrue(is_object(self::$o), sprintf("Did not get a %s object",self::$module));

		}


		public function testDahdiArray2Chans(){
      $arr = array(1);
      $ret = \dahdi_array2chans($arr);
      $this->assertEquals($ret, '1');

      $arr = array(1,2,3,4,5,6,7,8,9,10);
      $ret = \dahdi_array2chans($arr);
      $this->assertEquals($ret, '1-10');

      $arr = array();
      $ret = \dahdi_array2chans($arr);
      $this->assertFalse($ret);
		}

}
