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

	protected static $dahdi_cards;

	//Module name used in test output as self::$module. Can be anything unless you want to use this as something more.

	protected static $module = 'Dahdiconfig';

	//Change Moduleclass to your class name

	public static function setUpBeforeClass() {
			self::$f = FreePBX::create();
			self::$o = self::$f->Dahdiconfig;
			global $amp_conf;
			$amp_conf['DAHDIMOCKHW'] = 0;
			\FreePBX::Modules()->loadFunctionsInc('dahdiconfig');
			self::$dahdi_cards = \Mockery::mock('dahdi_cards')->makePartial();
	}

	//Stuff before the test

	public function setup() {}

	public function testRead_dahdi_scan() {
		$data = file(__DIR__."/scanset/1/dahdi_scan");
		self::$dahdi_cards->shouldReceive('execute_dahdi_scan')->andReturn($data);
		$this->assertNull(self::$dahdi_cards->read_dahdi_scan());
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testGet_spans() {
		$spans = file_get_contents(__DIR__."/scanset/1/dahdi_scan_spans");
		$this->assertEquals(trim($spans), json_encode(self::$dahdi_cards->get_spans()));
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testGet_hardware() {
		$hw = file_get_contents(__DIR__."/scanset/1/dahdi_scan_hardware");
		$this->assertEquals(trim($hw), json_encode(self::$dahdi_cards->get_hardware()));
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testGet_fxo_ports() {
		$hw = file_get_contents(__DIR__."/scanset/1/dahdi_scan_get_fxo_ports");
		$this->assertEquals(trim($hw), json_encode(self::$dahdi_cards->get_fxo_ports()));
	}

	public function testGet_all_modules() {
		global $amp_conf;
		$amp_conf['DAHDIMODULESLOC'] = '/invalid/file';
		$this->assertEquals(self::$dahdi_cards->get_all_modules(),array());

		$amp_conf['DAHDIMODULESLOC'] = __DIR__."/scanset/1/dahdi_modules";
		$parsed = file_get_contents(__DIR__."/scanset/1/dahdi_modules_parsed");
		$this->assertEquals(trim($parsed), json_encode(self::$dahdi_cards->get_all_modules()));

		self::$dahdi_cards->mockhw = 1;
		self::$dahdi_cards->get_all_modules();
		self::$dahdi_cards->mockhw = 0;
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testCalc_bchan_fxx() {
		$signallings = array("mfcr2","pri_cpe","pri_net","bri_cpe","bri_net","bri_net_ptmp","bri_cpe_ptmp");
		foreach($signallings as $sig) {
			$out = self::$dahdi_cards->calc_bchan_fxx(1,$sig,1,15);
			$this->assertEquals(array('fxx' => '1-15', 'endchan' => 15, 'startchan' => 1, 'usedchans' => 15, 'reservedchan' => 24),$out);

			$out = self::$dahdi_cards->calc_bchan_fxx(1,$sig,1,23);
			$this->assertEquals(array('fxx' => '1-23', 'endchan' => 23, 'startchan' => 1, 'usedchans' => 23, 'reservedchan' => 24),$out);

			$out = self::$dahdi_cards->calc_bchan_fxx(3,$sig,49,30);
			$this->assertEquals(array('fxx' => '49-63,65-79', 'endchan' => 79, 'startchan' => 49, 'usedchans' => 30, 'reservedchan' => 64),$out);
		}

		$out = self::$dahdi_cards->calc_bchan_fxx(1,'em',1,15);
		$this->assertEquals(array('fxx' => '1-15', 'endchan' => 15, 'startchan' => 1, 'usedchans' => 15, 'reservedchan' => 24),$out);

		$out = self::$dahdi_cards->calc_bchan_fxx(1,'em_w',1,23);
		$this->assertEquals(array('fxx' => '1-23', 'endchan' => 23, 'startchan' => 1, 'usedchans' => 23, 'reservedchan' => 24),$out);
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testCalc_bchan_fxx_exception1() {
		$this->setExpectedException('Exception', 'Exceded number of channels!');
		self::$dahdi_cards->calc_bchan_fxx(1,'pri_net',1,25);
	}

	/**
	 * @depends testRead_dahdi_scan
	 */
	public function testCalc_bchan_fxx_exception2() {
		$this->setExpectedException('Exception', 'Start channel is less than minimum channel!');
		self::$dahdi_cards->calc_bchan_fxx(3,'pri_net',1,25);
	}

	public function testGet_drivers_list() {
		$o = self::$dahdi_cards->get_drivers_list();
	}

	protected function tearDown() {
		\Mockery::close();
	}


	public function testDahdiArray2Chans(){
    $arr = array(1);
    $ret = \dahdi_array2chans($arr);
    $this->assertEquals('1',$ret);

    $arr = array(1,2,3,4,5,6,7,8,9,10);
    $ret = \dahdi_array2chans($arr);
    $this->assertEquals('1-10',$ret);

    $arr = array();
    $ret = \dahdi_array2chans($arr);
    $this->assertFalse($ret);
	}

}
