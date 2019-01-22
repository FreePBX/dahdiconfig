<?php
/**
* DAHDI CARDS
*
* This class contains all the functions necessary to manage DAHDi hardware.
*/
class dahdi_cards {
	private $analog_ports = array();	// stores all analog port info
	private $systemsettings = array(
		'tone_region' => 'us'
	);
	private $modprobe = array(		// modprobe array of values
		'module_name'=>'wctdm24xxp',
		'opermode_checkbox'=>FALSE,
		'opermode'=>'USA',
		'alawoverride_checkbox'=>FALSE,
        'alawoverride'=>0,
        
		'fxs_honor_mode_checkbox'=>FALSE,
		'fxs_honor_mode'=>0,
		'boostringer_checkbox'=>FALSE,
		'boostringer'=>0,
		'fastringer_checkbox'=>FALSE,
		'fastringer'=>0,
		'lowpower_checkbox'=>FALSE,
		'lowpower'=>0,
		'ringdetect_checkbox'=>FALSE,
		'ringdetect'=>0,
		'mwi_checkbox'=>FALSE,
		'mwi'=>'none',
		'neon_voltage'=>'',
		'neon_offlimit'=>'',
		'echocan_nlp_type'=>0,
		'echocan_nlp_threshold'=>'',
		'echocan_nlp_max_supp'=>'',
		'mode_checkbox' => FALSE,
		'mode'=>'any',
		'defaultlinemode_checkbox' => FALSE,
		'defaultlinemode' => 'T1'
	);
	private $globalsettings = array(		// global array of values
		'language'=>'en',
		'busydetect'=>'yes',
		'busycount'=>'10',
		'usecallerid'=>'yes',
		'callwaiting'=>'yes',
		'usecallingpres'=>'yes',
		'threewaycalling'=>'yes',
		'transfer'=>'yes',
		'cancallforward'=>'yes',
		'callreturn'=>'yes',
		'echocancel'=>'yes',
		'echocancelwhenbridged'=>'no',
		'echotraining'=>'no',
		'immediate'=>'no',
		'faxdetect'=>'no',
		'rxgain'=>'0.0',
		'txgain'=>'0.0'
	);
	private $configured_hdwr = array(); 	// The hardware already configured
	private $channels = array();
	private $chan_dahdi_conf;		// /etc/asterisk/chan_dahdi_additional.conf
	private $dahdi_scan;			// The output of a DAHDi scan
	private $detected_hdwr = array();	// Existing hardware on the sys
	private $drivers_list = array(); // List of available drivers
	private $echocans = array(		// List of possible echo cancellers
		'mg2'=>0,
		'kb1'=>1,
		'sec'=>2,
		'sec2'=>3,
		'hpec'=>4
	);
	private $error_msg = '';		// The latest error message
	private $fxo_ports = array();
	private $fxs_ports = array();
	private $groups = array();		// DAHDi Groups
	private $hardware = array();
	private $has_analog_hdwr = FALSE;	// If the sys has analog hardware
	private $has_vpm = FALSE;		// If the sys has echo can
	private $has_digital_hdwr = FALSE;	// If the sys has digital hardware
	private $has_gsm_hdwr = FALSE;	// If the sys has digital hardware
	private $hdwr_changes = FALSE;		// If hardware has changed since last configure
	private $module_name = 'wctdm24xxp';	// The module used
	private $ports_signalling = array(	// The ports signalling
		'ls' => array(),
		'ks' => array()
	);
	private $spancount = array();		// Per location
	private $spans = array();		// The current spans
	private $system_conf;			// /etc/dahdi/system.conf
	private $header;    //config file header
	public $modules = array();

	/**
	 * Constructor
	 */
	public function __construct () {
		foreach (glob(dirname(dirname(__FILE__))."/modules/*.module") as $filename) {
			$name = basename($filename,'.module');
			if (!class_exists('dahdi_'.$name)) {
				require_once($filename);
			}
			$class = 'dahdi_'.$name;
			$this->modules[$name] = new $class();
		}

		global $amp_conf;

		$check = array();
		$me = $amp_conf['AMPASTERISKUSER'];
		$check[] = $amp_conf['DAHDIMODULESLOC'];
		$check[] = $amp_conf['DAHDISYSTEMLOC'];
		$check[] = $amp_conf['DAHDIMODPROBELOC'];
		$this->mockhw = $amp_conf['DAHDIMOCKHW'];
		global $db;
		$nt = notifications::create($db);
		foreach($check as $list) {
			if (!file_exists($list)) {
				// Try to create the file if it doesn't exist
				//
				// Silence all warnings, because it's checked immediately
				// afer this.
				@mkdir(dirname($list), 0755, true);
				@touch($list);
			}

			clearstatcache(); // Lolphp. It shouldn't cache a false, but, clear it just in case.

			if (file_exists($list)) {
				$o = posix_getpwuid(fileowner($list));
				if($me != $o['name']) {
					$nt->add_error('dahdiconfig', str_replace("/","",$list), sprintf(_('File %s is not owned by %s'), $list, $me), sprintf(_("Please run '%s', then go back into the DAHDi Config Module"),'fwconsole chown'), "", false, true);
				} else {
					if($nt->exists('dahdiconfig', str_replace("/","",$list))) {
						$nt->delete('dahdiconfig', str_replace("/","",$list));
					}
				}
			} else {
				$o = array('name' => 'nofile');
				$nt->add_error('dahdiconfig', str_replace("/","",$list), sprintf(_('File %s does not exist.'), $list), sprintf(_("Please run 'touch %s' and then '%s' to create this file."), $list, 'fwconsole chown'), "", false, true);
			}
		}

		//Read Avalible Active modules
		if(file_exists($amp_conf['DAHDIMODULESLOC'])) {
			$handle = fopen($amp_conf['DAHDIMODULESLOC'], "r");
			while (($buffer = fgets($handle, 4096)) !== false) {
				if(!preg_match('/#/',$buffer)) {
					$buffer = trim($buffer);
					if(!empty($buffer)) {
						$this->drivers_list[] = $buffer;
					}
				}
			}
			fclose($handle);

		}

		$this->header = array();
		$this->header[] = "# -------------------------------------------------------------------------------;";
		$this->header[] = "# Do NOT edit this file as it is auto-generated by FreePBX. All modifications to ;";
		$this->header[] = "# this file must be done via the web gui. There are alternative files to make    ;";
		$this->header[] = "# custom modifications, details at: http://freepbx.org/configuration_files       ;";
		$this->header[] = "# -------------------------------------------------------------------------------;";
		$this->header[] = "#";
		$this->header[] = "";

		$this->header = implode("\n", $this->header);

		$this->original_global = array_keys($this->globalsettings);
		$this->original_modprobe = array_keys($this->modprobe);
		$this->original_system = array_keys($this->systemsettings);

		$this->load();
	}

	public function get_all_modules() {
		if($this->mockhw){
			return $this->get_all_modules_mock();
		}
		global $amp_conf;
		if(!file_exists($amp_conf['DAHDIMODULESLOC']) || !is_readable($amp_conf['DAHDIMODULESLOC'])) {
			return array();
		}
		$module_file = file_get_contents($amp_conf['DAHDIMODULESLOC']);
		$list = explode("\n",$module_file);
		$modules = array();
		foreach($list as $key => $item) {
			$item = trim($item);
			if(!preg_match('/\s/',$item) && !empty($item) && ($item != '#')) {
				if(preg_match('/^#/',$item)) {
					$item = str_replace('#','',$item);
					$modules[$item]['status'] = false;
				} else {
					//exec('modprobe '.$item,$out,$return_var);
					$modules[$item]['status'] = true;
				}
				$modules[$item]['type'] = ($list[$key-1] == '# UserDefined') ? 'ud' : 'sys';
			}
		}
		return $modules;
	}

	public function updateDigitalGroup($span, $updatedGroup, $groups) {
		if(empty($this->spans[$span])) {
			throw new \Exception("Invalid Span!");
		}
		$spanData = $this->spans[$span];
		$updatedGroup = json_decode($updatedGroup,true);
		$groups = json_decode($groups,true);
		uasort($groups, function($a,$b){
			if ($a['endchan'] == $b['endchan']) {
				return 0;
			}
			return ($a['endchan'] < $b['endchan']) ? -1 : 1;
		});

		$finalGroups = array();
		$lastgroup = 0;
		$groupint = 0;
		foreach($groups as $id => $group) {
			if($id == $updatedGroup['groupid']) {
				$group['usedchans'] = $updatedGroup['usedchans'];
			}
			$out = $this->new_calc_bchan_fxx($span,$spanData['signalling'],$group['startchan'],$group['usedchans']);
			$out['group'] = $group['group'];
			$out['context'] = 'from-digital';
			$finalGroups[] = $out;
			if($group['group'] !== 's') {
				$groupint = $group['group'];
			}
		}
		$lastGroup = end($finalGroups);
		if(($spanData['max_ch'] == $lastGroup['reservedchan']) && ($lastGroup['endchan'] + 1) == $lastGroup['reservedchan']) {
			//do nothing
		} elseif($lastGroup['endchan'] < $spanData['max_ch']) {
			$out = $this->new_calc_bchan_fxx($span,$spanData['signalling'],($lastGroup['endchan']+1));
			$out['group'] = $groupint + 1;
			$out['context'] = 'from-digital';
			$finalGroups[] = $out;
		}
		return $finalGroups;
	}

	public function new_calc_bchan_fxx($num,$signalling=NULL,$startchan=NULL,$usedchans=NULL) {
		if(empty($this->spans[$num])) {
			throw new \Exception("Invalid Span!");
		}
		$span = $this->spans[$num];

		$reservedchan = $span['reserved_ch'];

		$startchan = !is_null($startchan) ? $startchan : $span['min_ch'];
		if($startchan < $span['min_ch'] || $startchan > $span['max_ch']) {
			throw new \Exception("Start channel is less than minimum channel!");
		}
		if($startchan == $reservedchan) {
			$startchan++;
		}

		if(is_null($usedchans)) {
			$endchan = $span['max_ch'];
		} else {
			$endchan = $usedchans + $startchan;
			if($endchan < $reservedchan) {
				$endchan--;
			}
		}

		if($endchan > $span['max_ch'] || $endchan < $span['min_ch']) {
			throw new \Exception("Exceded number of channels!");
		}

		$fxx = '';
		$usedchans = 0;
		$span['signalling'] = !empty($span['signalling']) ? $span['signalling'] : 'pri_net';
		$sig = !empty($signalling) ? $signalling : $span['signalling'];
		if(substr($sig,0,3) == 'pri' || substr($sig,0,3) == 'bri' || substr($sig,0,5) == 'mfcr2') {
			for($i=$startchan;$i<=$endchan;$i++) {
				if($i == $reservedchan) {
					continue;
				}
				switch($i) {
					case $startchan:
						$fxx = $startchan;
					break;
					case $reservedchan - 1:
						$fxx .= "-".$i;
					break;
					case $reservedchan + 1:
						$fxx .= ($reservedchan != $startchan) ? ",".$i : $i;
					break;
					case $endchan:
						$fxx .= ($reservedchan != $i) ? "-".$i : '';
					break;
				}
				$usedchans++;
			}
			$fxx = rtrim($fxx, ',');
		} else {
			if($endchan == $reservedchan) {
				$endchan--;
			}
			$fxx = $startchan . "-" . $endchan;
			$usedchans = ($endchan - $startchan) + 1;
		}

		if($endchan == $reservedchan) {
			$endchan--;
		}

		return array(
			"fxx" => $fxx,
			"startchan" => (int)$startchan,
			"endchan" => (int)$endchan,
			"usedchans" => (int)$usedchans,
			"reservedchan" => (int)$reservedchan
		);
	}

	/**
	 * Calc Bchan Fxx
	 *
	 * Calculates the bchan and fxx strings for a given span
	 */
	public function calc_bchan_fxx($num,$signalling=NULL,$startchan=NULL,$usedchans=NULL) {
		return $this->new_calc_bchan_fxx($num,$signalling,$startchan,$usedchans);
	}

	/**
	 * Detect Hardware Changes
	 *
	 * Compare the known hardware with the current sys hardware and report
	 * if there are any changes
	 *
	 * @access public
	 * @return bool
	 */
	public function detect_hdwr_changes() {
		if ( count($this->detected_hdwr) != count($this->configured_hdwr)) {
			return TRUE;
		}

		foreach ($this->detected_hdwr as $location=>$detected) {
			if ( ! isset($this->configured_hdwr[$location])) {
				return TRUE;
			}

			$configured = $this->configured_hdwr[$location];

			$fields = array('device', 'basechan', 'type');
			foreach ($fields as $fld) {
				if ($configured[$fld] == $detected[$fld]) {
					continue;
				}

				if ($fld == 'analog' && $detected[$fld] == 'analog') {
					if (gettype($configured[$fld]) != gettype($detected[$fld])) {
						return TRUE;
					} else if (count($configured[$fld]) != count($detected[$fld])) {
						return TRUE;
					}


					for($i=0; $i<count($detected['port']); $i++) {
						if ($configured['port'][$i] == $detected['port'][$i]) {
							continue;
						}
						return TRUE;
					}

					continue;
				}

				return TRUE;
			}
		}

		return FALSE;
	}

	public function get_drivers_list() {
		return $this->drivers_list;
	}

	/**
	 * Get Advanced
	 *
	 * Get an advanced parameter
	 */
	public function get_globalsettings($param) {
		return isset($this->globalsettings[$param]) ? $this->globalsettings[$param] : '';
	}

	public function get_systemsettings($param) {
		return isset($this->systemsettings[$param]) ? $this->systemsettings[$param] : '';
	}

	public function get_modprobe($param) {
		return isset($this->modprobe[$param]) ? $this->modprobe[$param] : '';
	}

	/**
	 * Get All Advanced
	 *
	 * Get all advanced parameters
	 */
	public function get_all_globalsettings() {
		return $this->globalsettings;
	}

	public function get_all_systemsettings() {
		return $this->systemsettings;
	}

	public function get_all_modprobe($module=NULL) {
		if($module == 'wctc4xxp') {
			$o = array();
			$o['module_name'] = $module;
			$o['mode'] = $this->modprobe['mode'];
			$o['mode_checkbox'] = $this->modprobe['mode_checkbox'];
			return $o;
		} else {
			$o = $this->modprobe;
			unset($o['mode']);
			unset($o['mode_checkbox']);
			return $o;
		}
	}

	/**
	 * Get Analog Ports
	 *
	 * Get all analog port info
	 */
	public function get_analog_ports() {
		return $this->analog_ports;
	}

	/**
	 * Get Channels
	 *
	 * Get the channels array
	 *
	 * @access public
	 * @return array
	 */
	public function get_channels() {
		return $this->channels;
	}

	/**
	 * Get Hardware
	 *
	 * Get the hardware array
	 *
	 * @access public
	 * @return array
	 */
	public function get_hardware() {
		return $this->hardware;
	}

	/**
	 * Get FXO Ports
	 *
	 * Get the FXO ports
	 *
	 * @access public
	 * @return array
	 */
	public function get_fxo_ports() {
		return $this->fxo_ports;
	}

	/**
	 * Get FXS Ports
	 *
	 * Get the FXS ports
	 *
	 * @access public
	 * @return array
	 */
	public function get_fxs_ports() {
		return $this->fxs_ports;
	}

	/**
	 * Get FX(O|S)LS ports
	 *
	 * Get all the FXO and FXS ports with loop start signalling
	 *
	 * @access public
	 * @return array
	 */
	public function get_ls_ports() {
		return $this->ports_signalling['ls'];
	}

	/**
	 * Get Port
	 *
	 * Get the Analog Port assoc array that is associated
	 * with the given port number
	 */
	public function get_port($num) {
		return $this->analog_ports[$num];
	}

	/**
	 * Get Span Count
	 *
	 * Get the span count per location
	 *
	 * @access public
	 * @return array
	 */
	public function get_span_count($loc) {
		return $this->spancount[$loc];
	}

	/**
	 * Get Spans
	 *
	 * Get the digital spans
	 *
	 * @access public
	 * @return array
	 */
	public function get_spans() {
		return $this->spans;
	}

	/**
	 * Update the span definitions
	 * @method set_spans
	 * @param  Array    $spans Array of span information
	 */
	public function set_spans($spans) {
		$this->spans = $spans;
	}

	/**
	 * Get Span
	 *
	 * Get a digital span and all its info
	 */
	public function get_span($num) {
		return $this->spans[$num];
	}

	/**
	 * Set single span data
	 * @method set_span
	 * @param  integer   $num  The span number
	 * @param  array   $data Array of data
	 */
	public function set_span($num, $data) {
		$this->spans[$num] = $data;
	}

	/**
	 * Has VPM
	 *
	 * Return if the cards have a vpm module
	 */
	public function has_vpm() {
		return $this->has_vpm;
	}

	/**
	 * Hardware Changes
	 *
	 * Return if hdwr has changed or not
	 *
	 * @access public
	 * @return bool
	 */
	public function hdwr_changes() {
		return $this->hdwr_changes;
	}

	/**
	 * Load
	 *
	 * Load all the information the various locations (database, system.conf, chan_dahdi.conf)
	 */
	public function load() {
		$this->read_configured_hdwr();
		$this->read_dahdi_scan();
		$this->read_system_conf();
		$this->read_chan_dahdi_conf();
		$this->read_dahdi_modprobe();
		$this->read_dahdi_globalsettings();
		$this->read_dahdi_systemsettings();
		$this->read_dahdi_analog();
		$this->read_dahdi_analog_custom();
		$this->read_dahdi_spans();
	}

	public function checkHardware() {
		global $amp_conf;
		$this->hdwr_changes = $this->detect_hdwr_changes();
		if ($this->hdwr_changes && file_exists('/usr/sbin/dahdi_genconf') && file_exists('/usr/sbin/dahdi_cfg')) {
			if(file_exists('/etc/dahdi/system.conf') && is_readable('/etc/dahdi/system.conf') && is_writable('/etc/dahdi/system.conf')) {
				$contents = file_get_contents('/etc/dahdi/system.conf');
				if(empty($contents)) {
					exec('/usr/sbin/dahdi_genconf system 2>/dev/null');
					exec('/usr/sbin/dahdi_cfg 2>/dev/null');
				}
			} elseif(!file_exists('/etc/dahdi/system.conf')) {
				exec('/usr/sbin/dahdi_genconf system 2>/dev/null');
				exec('/usr/sbin/dahdi_cfg 2>/dev/null');
			}
			$this->read_dahdi_scan(); //TODO why?
			$this->write_detected();
			$this->write_spans();
			$this->write_analog_signalling();
		}
	}

	/**
	 * Read Configured Hardware
	 *
	 * Go check out the database and read in the configured hardware
	 */
	public function read_configured_hdwr() {
		global $db;

		$sql = "SELECT * FROM dahdi_configured_locations ORDER BY basechan";

		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			die_freepbx($results->getDebugInfo());
			return false;
		}

		foreach ($results as $row) {
			if ( ! isset($this->configured_hdwr[$row['location']])) {
				$this->configured_hdwr[$row['location']] = array();
			}

			$this->configured_hdwr[$row['location']]['device'] = $row['device'];
			$this->configured_hdwr[$row['location']]['basechan'] = $row['basechan'];
			$this->configured_hdwr[$row['location']]['type'] = $row['type'];
		}
	}

	/**
	 * Read DAHDi Advanced
	 *
	 * Load all the configuration information in /etc/dahdi/system.conf
	 */
	public function read_dahdi_modprobe() {
		global $db;

		if(isset($_REQUEST['module_name'])) {
			$sql = "SELECT settings FROM dahdi_advanced_modules WHERE module_name = '".$db->escapeSimple($_REQUEST['module_name'])."'";
			$module_name = $_REQUEST['module_name'];
		} else {
			$sql = "SELECT settings FROM dahdi_advanced_modules WHERE module_name = '".$this->modprobe['module_name']."'";
			$module_name = $this->modprobe['module_name'];
		}
		$settings = sql($sql, 'getOne');
		if($settings) {
			$this->modprobe = json_decode($settings,TRUE);
			$this->modprobe['module_name'] = $module_name;
		}
	}

	public function read_all_dahdi_modprobe() {
		$sql = "SELECT module_name, settings FROM dahdi_advanced_modules";
		$settings = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
		if($settings) {
			return $settings;
		} else {
			return FALSE;
		}
	}

	public function read_dahdi_globalsettings() {
		$tone_sql = "SELECT keyword, val, default_val FROM dahdi_advanced WHERE type='chandahdi'";
		$settings = sql($tone_sql, 'getAll', DB_FETCHMODE_ASSOC);
		if($settings) {
			foreach($settings as $set) {
				$key = $set['keyword'];
				$this->globalsettings[$key] = isset($set['val']) ? $set['val'] : $set['default_val'];
			}
		}
	}

	public function read_dahdi_systemsettings() {
		$tone_sql = "SELECT keyword, val, default_val FROM dahdi_advanced WHERE type='system'";
		$settings = sql($tone_sql, 'getAll', DB_FETCHMODE_ASSOC);
		if($settings) {
			foreach($settings as $set) {
				$key = $set['keyword'];
				$this->systemsettings[$key] = isset($set['val']) ? $set['val'] : $set['default_val'];
			}
		}
	}

	public function read_dahdi_analog() {
		global $db;

		$sql = 'SELECT * FROM dahdi_analog';

		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			die_freepbx($results->getDebugInfo());
			return false;
		}

		foreach($results as $res) {
			$this->analog_ports[$res['port']] = $res;
			if ($res['signalling'] == 'ls') {
				$this->ports_signalling['ls'][] = $res['port'];
			} else {
				$this->ports_signalling['ks'][] = $res['port'];
			}
		}
	}

	public function read_dahdi_analog_custom() {
		global $db;

		$sql = 'SELECT * FROM dahdi_analog_custom';

		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			die_freepbx($results->getDebugInfo());
			return false;
		}

		foreach($results as $res) {
			$this->analog_ports[$res['dahdi_analog_port']]['custom'][$res['keyword']] = $res['val'];
		}
	}

	/**
	 * Read DAHDi Spans
	 *
	 * Read in all the dahdi_spans info from the database
	 */
	public function read_dahdi_spans() {
		global $db;

		$sql = 'SELECT * FROM dahdi_spans';

		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			die_freepbx($results->getDebugInfo());
			return false;
		}

		foreach ($results as $span) {
			foreach ($span as $key=>$val) {
				if ($val == '') {
					continue;
				}
				$this->spans[$span['span']][$key] = $val;
			}
			$this->spans[$span['span']]['context'] = (isset($this->spans[$span['span']]['context']) && !empty($this->spans[$span['span']]['context'])) ? $this->spans[$span['span']]['context'] : 'from-digital';
			if(!empty($this->spans[$span['span']]['additional_groups'])) {
				$this->spans[$span['span']]['additional_groups'] = json_decode($this->spans[$span['span']]['additional_groups'],true);
			}
			if(empty($this->spans[$span['span']]['additional_groups'])) {
				$o = $this->calc_bchan_fxx($span['span']);
				$this->spans[$span['span']]['additional_groups'] = array(0 => array(
					"group" => 0,
					"context" => 'from-digital',
					"usedchans" => $this->spans[$span['span']]['totchans'],
					"startchan" => $this->spans[$span['span']]['min_ch'],
					"endchan" => $this->spans[$span['span']]['max_ch'],
					"fxx" => $o['fxx']
				));
			}
		}
	}

	/**
	 * Read System Conf
	 *
	 * Load all the configuration information in /etc/dahdi/system.conf
	 */
	public function read_system_conf() {
		//return false;

		//TODO: neverending loop
		$nomore = false;
		$ctr = 0;
		do {
			if(!file_exists('/etc/dahdi/system.conf') || !is_readable('/etc/dahdi/system.conf')) {
				return FALSE;
			}
			$this->systemsettings_conf = file_get_contents('/etc/dahdi/system.conf');
			if (! $this->systemsettings_conf) {
				return FALSE;
			}

			$lines = explode("\n", $this->systemsettings_conf);

			$hasaline = false;
			foreach ($lines as $line) {
				// its a comment, like this line
				if (substr($line,0,1) == '#' || trim($line) == '') {
					continue;
				}
				$hasaline = true;
				break;
			}

			if ( ! $hasaline) {
				if(!file_exists('/usr/sbin/dahdi_genconf')) {
					break;
				}
				exec('/usr/sbin/dahdi_genconf system 2>/dev/null',$output,$return_var);
				if($return_var != '0') {
					//If genconf returns an error then we should abort otherwise we will be in a neverending loop
					break;
				}
			}

			$ctr++;
			if ($ctr > 2) {
				$nomore = true;
			}
		} while ( !$hasaline || $nomore);

		if ( ! $hasaline) {
			return false;
		}

		for($i=0;$i<count($lines);$i++) {

			$line = $lines[$i];
			if ($line == '' || (strpos($line, '#') === 0)) {
				continue;
			}

			$begin = substr($line, 0, 5);
			switch($begin) {
				case 'fxoks':
				case 'fxsks':
					$ks_ports = explode('=',$line);
					$ks_ports = $ks_ports[1];
					$this->ports_signalling['ks'] = array_merge($this->ports_signalling['ks'], dahdi_chans2array($ks_ports));
				break;
				case 'fxols':
				case 'fxsls':
					$ls_ports = explode('=',$line);
					$ls_ports = $ls_ports[1];
					$this->ports_signalling['ls'] = array_merge($this->ports_signalling['ls'], dahdi_chans2array($ls_ports));
				break;
				case 'echoc': //echocanceller
					$this->has_echo_can = true;
				break;
				case 'span=':
					$info = explode('=', $line);
					list($num, $timing, $lbo, $framing, $coding) = explode(',', $info[1]);
					$spaninfo = explode(',', $info[1]);
					$yellow = isset($spaninfo[5]) ? $spaninfo[5] : '';
					$this->spans[$num]['timing'] = $timing;
					$this->spans[$num]['lbo'] = $lbo;
					$this->spans[$num]['framing'] = $framing;
					$this->spans[$num]['coding'] = $coding.(($yellow)?"/$yellow":"");
				break;
				default:
			}
		}

		// write echo can if it doesn't exist, for ABE only ???
	}

	/**
	 * Read Chan Dahdi Conf
	 *
	 * Read from chan_dahdi_additional.conf and get any useful information
	 */
	public function read_chan_dahdi_conf() {
		global $db;

		$sql = 'SELECT * FROM dahdi WHERE id = -1 AND keyword != "account" AND flags != 1';
		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			return($results->getMessage());
		}

		$additional = array();
		foreach ($results as $result) {
			$additional[$result['keyword']] = $result['data'];
		}

		unset($results);
		unset($result);

		$sql = 'SELECT * FROM dahdi WHERE keyword != "account" and flags != 1';
		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			return($results->getMessage());
		}

		$accounts = array();
		foreach ($results as $result) {
			if ( ! isset($accounts[$result['id']])) {
				$accounts[$result['id']] = array();
			}

			switch($result['keyword']) {
				case 'record_in':
				case 'record_out':
				case 'dial':
				break;
				default:
				$accounts[$result['id']][$result['keyword']] = $result['data'];
			}
		}

		unset($results);
		unset($result);
		unset($sql);

		foreach ($accounts as $account) {
			$account = array_merge($account, $additional);
			$this->channels[$account['channel']] = $account;
		}

		$sql = 'SELECT * FROM dahdi_spans';
		$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		if (DB::IsError($results)) {
			die_freepbx($results->getDebugInfo());
		}

		$flds = array('framing', 'signalling', 'coding', 'definedchans', 'switchtype', 'syncsrc', 'lbo', 'pridialplan', 'prilocaldialplan', 'additional_groups');
		foreach ($results as $span) {
			foreach ($flds as $fld) {
				if ( ! $span[$fld]) {
					continue;
				}

				$this->spans[$span['span']][$fld] = $span[$fld];

			}
		}

		return true;
	}

	public function execute_dahdi_scan() {
		if(!file_exists('/usr/sbin/dahdi_scan')) {
			return false;
		}
		if(!is_executable('/usr/sbin/dahdi_scan')) {
			throw new \Exception(_("dahdi_scan exists but can not be run as this user!"));
		}
		if($this->mockhw){
				$dahdi_scan_output = $this->dahdi_scan_mock();
				$return_var = '0';
		}else{
			exec('/usr/sbin/dahdi_scan 2>/dev/null',$dahdi_scan_output,$return_var);
		}
		if($return_var != '0') {
			return false;
		}
		return $dahdi_scan_output;
	}

	/**
	 * Read DAHDi Scan
	 *
	 * Read all the information given in the DAHDi Scan script
	 */
	public function read_dahdi_scan() {
		$dahdi_scan_output = $this->execute_dahdi_scan();
		if($dahdi_scan_output === false) {
			return;
		}
		unset($this->fxo_ports);
		unset($this->fxs_ports);
		$this->fxo_ports = array();
		$this->fxs_ports = array();

		foreach ($dahdi_scan_output as $line) {
			if ($line == '') {
				continue;
			} else if (preg_match('/^\[([-a-zA-Z0-9_][-a-zA-Z0-9_]*)\]/', $line, $matches)) {
				$cxt = $matches[1];
				$cxts[$cxt] = array();
				continue;
			}

			list($var, $val) = explode('=', $line);

			if ($var == 'port' && strpos($val, 'FXO')) {
				$num = explode(',',$val);
				$num = $num[0];
				$this->fxo_ports[] = $num;
			} else if ($var == 'port' && strpos($val, 'FXS')) {
				$num = explode(',',$val);
				$num = $num[0];
				$this->fxs_ports[] = $num;
			}

			if ($var == 'type' && strpos($val,'analog') !== FALSE) {
				$this->has_analog_hdwr = TRUE;
			} else if ($var == 'type' && strpos($val, 'digital') !== FALSE) {
				$this->has_digital_hdwr = TRUE;
			}

			if($var == 'devicetype' && strpos($val,'W400') !== FALSE) {
				$this->has_gsm_hdwr = TRUE;
			}
		}

		/* If there is a DAHDI_Dummy then there is no hardware to parse */
		if (isset($cxts)) foreach ($cxts as $cxt) {
			if ((!array_key_exists('description', $cxt)) || (strpos($cxt['description'],'DAHDI_DUMMY') === false)) {
				continue;
			}
			return false;
		}

		$spans = dahdi_config2array($dahdi_scan_output);

		if (count($spans) == 0) {
			$this->has_digital_hdwr = FALSE;
			$this->has_analog_hdwr = FALSE;
			$this->has_gsm_hdwr = FALSE;
			return;
		}

		foreach ($spans as $key=>$span) {
			if (strpos($span['devicetype'], 'VPMADT032') !== FALSE) {
				$this->has_vpm = true;
			}

			if(isset($this->hardware[$span['location']]['type']) && ($this->hardware[$span['location']]['type'] != $span['type'])) {
				$this->hardware[$span['location']] = array();
				$this->hardware[$span['location']]['device'] = $span['devicetype'];
				$this->hardware[$span['location']]['basechan'] = $span['basechan'];
				$this->hardware[$span['location']]['type'] = 'hybrid';
				if($span['type'] == 'analog') {
					$this->detected_hdwr[$span['location']] = $this->hardware[$span['location']];
					continue;
				}
			} elseif ($span['type'] == 'analog' && strpos($span['devicetype'],'W400') === FALSE) {
				$this->hardware[$span['location']] = array();
				$this->hardware[$span['location']]['device'] = $span['devicetype'];
				$this->hardware[$span['location']]['basechan'] = $span['basechan'];
				$this->hardware[$span['location']]['type'] = $span['type'];
				$this->detected_hdwr[$span['location']] = $this->hardware[$span['location']];
				continue;
			}

			if (strpos($span['description'], 'ztdummy') !== false) {
				continue;
			}

			$this->spans[$key] = array();
			foreach ($span as $attr=>$val) {
				$this->spans[$key]['dsid'] = $key;
				$this->spans[$key][$attr] = $span[$attr];
				$this->spans[$key]['type'] = (isset($this->spans[$key]['devicetype']) && ($this->spans[$key]['devicetype'] == 'W400')) ? 'gsm' : (isset($this->spans[$key]['type']) ? $this->spans[$key]['type'] : '');
				$this->spans[$key]['additional_groups'] = array();
				switch($attr) {
					case 'location':
						if ( ! isset($this->spancount[$val]) ) {
							$this->spancount[$val] = 0;
						}

						$this->spancount[$val]++;

						if (!isset($this->hardware[$val]) ) {
							$this->hardware[$val] = array();
							$this->hardware[$val]['device'] = $span['devicetype'];
							$this->hardware[$val]['basechan'] = $span['basechan'];
							$this->hardware[$val]['type'] = $span['type'];
							$this->detected_hdwr[$span['location']] = $this->hardware[$span['location']];
						}
					break;
					case 'totchans':
						$parts = explode('-',$span['type']);
						if(empty($parts[1])) {
							switch ($span['totchans']) {
								case 3:
									$this->spans[$key]['spantype'] = 'BRI';
								break;
								case 25:
									$this->spans[$key]['spantype'] = 'T1';
								break;
								case 31:
									$this->spans[$key]['spantype'] = 'E1';
								break;
							}
						} else {
							$this->spans[$key]['spantype'] = $parts[1];
						}
						//list($dummy, $this->spans[$key]['spantype']) = explode('-',$span['type']);
						$this->spans[$key]['min_ch'] = $span['basechan'];
						$this->spans[$key]['max_ch'] = $span['basechan'] + $span['totchans'] - 1;

						switch ($span['totchans']) {
							case 2:
								$this->spans[$key]['definedchans'] = 1;
								$this->spans[$key]['reserved_ch'] = $span['basechan'] + 1;
							break;
							case 3:
								$this->spans[$key]['definedchans'] = 2;
								$this->spans[$key]['reserved_ch'] = $span['basechan'] + 2;
							break;
							case 24:
								$this->spans[$key]['definedchans'] = 23;
								$this->spans[$key]['reserved_ch'] = $span['basechan'] + 23;
							break;
							case 31:
								$this->spans[$key]['definedchans'] = 31;
								$this->spans[$key]['reserved_ch'] = $span['basechan'] + 15;
							break;
							default:
								$this->spans[$key]['definedchans'] = 0;
							break;
						}
					break;
					case 'lbo':
						switch($val){
							//TODO: do we localize "feet?"
							case '0 db (CSU)/0-133 feet (DSX-1)':
								$this->spans[$key]['lbo'] = 0;
							break;
							case '133-266 feet (DSX-1)':
								$this->spans[$key]['lbo'] = 1;
							break;
							case '266-399 feet (DSX-1)':
								$this->spans[$key]['lbo'] = 2;
							break;
							case '399-533 feet (DSX-1)':
								$this->spans[$key]['lbo'] = 3;
							break;
							case '533-655 feet (DSX-1)':
								$this->spans[$key]['lbo'] = 4;
							break;
							case '-7.5db (CSU)':
								$this->spans[$key]['lbo'] = 5;
							break;
							case '-15db (CSU)':
								$this->spans[$key]['lbo'] = 6;
							break;
							case '-22.5db (CSU)':
								$this->spans[$key]['lbo'] = 7;
							break;
							default:
								$this->spans[$key]['lbo'] = 0;
							break;
						}
					break;
					default:
					break;
				}
			}
		}
	}

	/**
	 * Set Analog Signalling
	 *
	 * Take the port number and signalling (ls/ks) and update the
	 * ports_signalling array
	 */
	public function set_analog_signalling($num, $port) {
		$opp = ($port['signalling'] == 'ks') ? 'ls' : 'ks';
		$key = array_search($num, $this->ports_signalling[$opp]);
		if ($key) {
			unset($this->ports_signalling[$opp][$key]);
		}

		if ( ! in_array($num, $this->ports_signalling[$port['signalling']])) {
			$this->ports_signalling[$port['signalling']][] = $num;
		}

		$this->analog_ports[$num]['group'] = $port['group'];
		$this->analog_ports[$num]['context'] = $port['context'];

		needreload();
	}

	public function update_dahdi_analog_custom($params) {
		global $db;
		foreach ($params as $span => $custom_settings) {
			foreach($custom_settings as $keyword => $val) {
				if (isset($val) && ($val != "")) {
					$sql = "REPLACE INTO dahdi_analog_custom (dahdi_analog_port, keyword, val) VALUES ('"
					       . $db->escapeSimple($span) .    "', '"
					       . $db->escapeSimple($keyword) . "', '"
					       . $db->escapeSimple($val) .     "')";
					sql($sql);
					needreload();
				}
			}
		}
	}

	/**
	 * Update DAHDi Advanced
	 *
	 * Update the database for dahdi advanced and then update the proper files
	 *
	 * @access public
	 * @param array $params An array of parameters
	 * @return bool
	 */
	public function update_dahdi_modprobe($params) {
		global $db;

		if(isset($params['module_name'])) {
			$module_name = $params['module_name'];
			unset($params['module_name']);
			$sql = "REPLACE INTO dahdi_advanced_modules (module_name, settings) VALUES ('".$db->escapeSimple($module_name)."', '".$db->escapeSimple(json_encode($params))."')";
			sql($sql);
			needreload();
		}
	}

	public function update_dahdi_modules($params) {
		global $db;

		//I question if we should write this to the DB....but naw.
		//We should write it directly to the file because if the user changes things manually we need to know about those changes instantly
		//We will add the write function here and make them separate functions incase we want to change this in the future though.
		$this->write_dahdi_modules($params);
	}

	public function update_dahdi_globalsettings($params) {
		global $db;
		foreach($params as $k => $v) {
			if(isset($v) && ($v != "")) {
				$additional = array_key_exists($k,$this->globalsettings) ? 0 : 1;
				$sql = "REPLACE INTO dahdi_advanced (val, keyword, additional, type) VALUES ('".$db->escapeSimple($v)."', '".$db->escapeSimple($k)."', ".$additional.", 'chandahdi')";
				sql($sql);
				needreload();
			}
		}
	}

	public function update_dahdi_systemsettings($params) {
		global $db;
		foreach($params as $k => $v) {
			if(isset($v) && ($v != "")) {
				$additional = array_key_exists($k,$this->systemsettings) ? 0 : 1;
				$sql = "REPLACE INTO dahdi_advanced (val, keyword, additional, type) VALUES ('".$db->escapeSimple($v)."', '".$db->escapeSimple($k)."', ".$additional.", 'system')";
				sql($sql);
				needreload();
			}
		}
	}

	/**
	 * Update Span
	 *
	 * Update the span info and write it to the appropriate files
	 */
	public function update_span($editspan) {
		$num = $editspan['span'];

		if ($editspan['fac'] == 'CCS/HDB3/CRC4') {
			$this->spans[$num]['framing'] = 'CCS/HDB3';
			$this->spans[$num]['coding'] = 'CRC4';
		} else {
			list($framing, $coding) = explode('/',$editspan['fac']);
			$this->spans[$num]['framing'] = $framing;
			$this->spans[$num]['coding'] = $coding;
		}

		$this->spans[$num]['fac'] = $editspan['fac'];
		$this->spans[$num]['signalling'] = $editspan['signalling'];
		$this->spans[$num]['syncsrc'] = $editspan['syncsrc'];
		$this->spans[$num]['switchtype'] = $editspan['switchtype'];
		$this->spans[$num]['lbo'] = $editspan['lbo'];
		$this->spans[$num]['pridialplan'] = $editspan['pridialplan'];
		$this->spans[$num]['prilocaldialplan'] = $editspan['prilocaldialplan'];
		$this->spans[$num]['group'] = $editspan['group'];
		$this->spans[$num]['context'] = $editspan['context'];
		$this->spans[$num]['reserved_ch'] = $editspan['reserved_ch'];
		$this->spans[$num]['priexclusive'] = $editspan['priexclusive'];
		$this->spans[$num]['rxgain'] = !empty($editspan['rxgain']) ? $editspan['rxgain'] : '0.0';
		$this->spans[$num]['txgain'] = !empty($editspan['txgain']) ? $editspan['txgain'] : '0.0';
		$this->spans[$num]['additional_groups'] = !empty($editspan['additional_groups']) ? $editspan['additional_groups'] : array();

		if ($editspan['signalling'] == "mfcr2") {
		    $this->spans[$num]['mfcr2_variant'] 				= $editspan['mfcr2_variant'] ? $editspan['mfcr2_variant'] : 'ITU';
		    $this->spans[$num]['mfcr2_max_ani'] 				= $editspan['mfcr2_max_ani'] ? $editspan['mfcr2_max_ani'] : 10;
		    $this->spans[$num]['mfcr2_max_dnis'] 				= $editspan['mfcr2_max_dnis'] ? $editspan['mfcr2_max_dnis'] : 4;
		    $this->spans[$num]['mfcr2_get_ani_first'] 			= $editspan['mfcr2_get_ani_first'] ? $editspan['mfcr2_get_ani_first'] : 'no';
		    $this->spans[$num]['mfcr2_category'] 				= $editspan['mfcr2_category'] ? $editspan['mfcr2_category'] : 'national_subscriber';
		    $this->spans[$num]['mfcr2_logdir'] 					= $editspan['mfcr2_logdir'] ? $editspan['mfcr2_logdir'] : '';
		    $this->spans[$num]['mfcr2_call_files'] 				= $editspan['mfcr2_call_files'] ? $editspan['mfcr2_call_files'] : '';
		    $this->spans[$num]['mfcr2_logging'] 				= $editspan['mfcr2_logging'] ? $editspan['mfcr2_logging'] : '';
		    $this->spans[$num]['mfcr2_mfback_timeout'] 			= $editspan['mfcr2_mfback_timeout'] ? $editspan['mfcr2_mfback_timeout'] : -1;
		    $this->spans[$num]['mfcr2_metering_pulse_timeout'] 	= $editspan['mfcr2_metering_pulse_timeout'] ? $editspan['mfcr2_metering_pulse_timeout'] : -1;
		    $this->spans[$num]['mfcr2_allow_collect_calls'] 	= $editspan['mfcr2_allow_collect_calls'] ? $editspan['mfcr2_allow_collect_calls'] : 'no';
		    $this->spans[$num]['mfcr2_double_answer'] 			= $editspan['mfcr2_double_answer'] ? $editspan['mfcr2_double_answer'] : 'yes';
		    $this->spans[$num]['mfcr2_immediate_accept']		= $editspan['mfcr2_immediate_accept'] ? $editspan['mfcr2_immediate_accept'] : 'no';
		    $this->spans[$num]['mfcr2_accept_on_offer'] 		= $editspan['mfcr2_accept_on_offer'] ? $editspan['mfcr2_accept_on_offer'] : 'yes';
		    $this->spans[$num]['mfcr2_skip_category'] 			= $editspan['mfcr2_skip_category'] ? $editspan['mfcr2_skip_category'] : 'no';
		    $this->spans[$num]['mfcr2_forced_release'] 			= $editspan['mfcr2_forced_release'] ? $editspan['mfcr2_forced_release'] : 'no';
		    $this->spans[$num]['mfcr2_charge_calls'] 			= $editspan['mfcr2_charge_calls'] ? $editspan['mfcr2_charge_calls'] : 'yes';
		    $this->spans[$num]['mfcr2_advanced_protocol_file'] 	= $editspan['mfcr2_advanced_protocol_file'] ? $editspan['mfcr2_advanced_protocol_file'] : '';
		}

		$this->write_spans();
		$this->write_system_conf();

		needreload();
	}

	/**
	 * Write Analog Signalling
	 *
	 * Take the analog ports and write them to the dahdi_analog table
	 */
	public function write_analog_signalling() {
		global $db;

		$sql = 'TRUNCATE dahdi_analog';

		$result = $db->query($sql);
		if (DB::IsError($result)) {
			die_freepbx($result->getDebugInfo());
		}
		unset($result);

		$ports = array();
		foreach ($this->fxo_ports as $fxo) {
			if (in_array($fxo, $this->ports_signalling['ls'])) {
				$sig = 'ls';
			} else {
				$sig = 'ks';
			}

			$group = (isset($this->analog_ports[$fxo]['group']))?$this->analog_ports[$fxo]['group']:0;
			$context = (isset($this->analog_ports[$fxo]['context']))?$this->analog_ports[$fxo]['context']:'from-analog';

			$ports[] = "($fxo, 'fxo', '$sig', '".$db->escapeSimple($group)."', '".$db->escapeSimple($context)."')";
		}

		foreach ($this->fxs_ports as $fxs) {
			if (in_array($fxs, $this->ports_signalling['ls'])) {
				$sig = 'ls';
			} else {
				$sig = 'ks';
			}

			$group = (isset($this->analog_ports[$fxs]['group']))?$this->analog_ports[$fxs]['group']:0;
			$context = (isset($this->analog_ports[$fxs]['context']))?$this->analog_ports[$fxs]['context']:'from-analog';

			$ports[] = "($fxs, 'fxs', '$sig', '".$db->escapeSimple($group)."', '".$db->escapeSimple($context)."')";
		}

		if (sizeof($ports) <= 0) {
			return true;
		}

		$sql = 'INSERT INTO dahdi_analog (port, type, signalling, `group`, context) VALUES '.implode(', ',$ports);

		$result = $db->query($sql);
		if (DB::IsError($result)) {
			die_freepbx($result->getDebugInfo());
		}

		return true;
	}

	/**
	 * Write Detected
	 *
	 * Write the detected hardware to the dahdi_configured_locations table
	 */
	public function write_detected() {
		global $db;

		$sql = "TRUNCATE TABLE dahdi_configured_locations";
		$result = $db->query($sql);
		if (DB::IsError($result)) {
			die_freepbx($result->getDebugInfo());
		}
		unset($result);

		//If there is no newly detected hardware
		//then return false otherwise we will crash out
		//when we try to add nothing to the db
		//we have to clear out the configured locations regardless
		//otherwise our groups are not regenerated
		if(empty($this->detected_hdwr)) {
			return false;
		}

		$flds = array('location', 'device', 'basechan', 'type');
		$inserts = array();
		foreach ($this->detected_hdwr as $loc=>$hdwr) {
			$insert = array();
			foreach ($flds as $fld) {
				if ($fld == 'location') {
					$insert[] = "'$loc'";
				} else {
					$insert[] = "'{$hdwr[$fld]}'";
				}
			}

			$inserts[] = '('.implode(', ',$insert).')';
		}
		$sql = 'INSERT INTO dahdi_configured_locations ('.implode(', ',$flds).') VALUES '.implode(', ',$inserts);

		$result = $db->query($sql);
		if (DB::IsError($result)) {
			die_freepbx($result->getDebugInfo());
		}

		return true;
	}

	/**
	 * Write Spans
	 *
	 * Write the current spans to the database
	 */
	public function write_spans() {
		global $db;

		$sql = "TRUNCATE dahdi_spans";
		$result = $db->query($sql);
		if (DB::IsError($result)) {
			die_freepbx($result);
		}
		unset($result);

		$flds = array('span', 'manufacturer', 'framing', 'definedchans', 'coding', 'signalling', 'switchtype', 'syncsrc', 'lbo', 'pridialplan', 'prilocaldialplan', 'group', 'context', 'reserved_ch', 'priexclusive','additional_groups','type','txgain','rxgain', 'mfcr2_variant', 'mfcr2_get_ani_first', 'mfcr2_max_ani', 'mfcr2_max_dnis', 'mfcr2_category', 'mfcr2_call_files', 'mfcr2_logdir', 'mfcr2_logging', 'mfcr2_mfback_timeout', 'mfcr2_metering_pulse_timeout', 'mfcr2_allow_collect_calls', 'mfcr2_double_answer', 'mfcr2_immediate_accept', 'mfcr2_forced_release', 'mfcr2_charge_calls', 'mfcr2_accept_on_offer', 'mfcr2_skip_category', 'mfcr2_advanced_protocol_file');

		$sql = 'INSERT INTO dahdi_spans (`'.implode('`, `',$flds).'`) VALUES ';

		$inserts = array();
		foreach ($this->spans as $key=>$span) {
			$values = array();
			if(preg_match('/(.*)\-.*/i',$span['type'],$matches)) {
				$span['type'] = $matches[1];
			}
			foreach ($flds as $fld) {
				switch($fld) {
					case 'additional_groups':
						$values[] = "'".json_encode($span[$fld])."'";
					break;
					case 'span':
						$values[] = "'$key'";
					break;
					default:
						// If the variable is undefined, this is a bug.
						if (!isset($span[$fld])) {
							$values[] = "''";
							// throw new \Exception("Error reading $fld from span $key - ".json_encode($this->spans));
						} else {
							$values[] = "'{$span[$fld]}'";
						}
					break;
				}
			}

			$inserts[] = '('.implode(', ',$values).')';
			unset($values);
			$result = $db->query($sql.implode(', ', $inserts));
			if (DB::IsError($result)) {
				die_freepbx($result->getDebugInfo());
			}
			unset($inserts);
		}
	}

	/**
	 * Write System Conf
	 *
	 * Take all the information received and write a new /etc/dahdi/system.conf
	 */
	public function write_system_conf() {
		$fxx = array();
		$output = array();
		$bchan = '';
		$dchan = '';
		$hardhdlc = '';

		global $amp_conf;
		$file = $amp_conf['DAHDISYSTEMLOC'];

		global $db;
		$nt =& notifications::create($db);
		if ((file_exists($file) && !is_writable($file)) || (!file_exists($file) && !is_writable(dirname($file)))) {
			$nt->add_error('dahdiconfig', 'SYSTEMCONF', sprintf(_('Unable to write to %s'),$file), sprintf(_("Please change permissions on %s"),$file), "", false, true);
			return false;
		} else {
			if($nt->exists('dahdiconfig', 'SYSTEMCONF')) {
				$nt->delete('dahdiconfig', 'SYSTEMCONF');
			}
		}

		foreach ($this->spans as $num=>$span) {
			if (empty($span['signalling'])) {
				continue;
			}
			if ($span['type'] == 'gsm') {
				//continue;
			}

			$span['fac'] = str_replace('/',',',$span['framing']).','.$span['coding'];
			$span['lbo'] = ($span['lbo']) ? $span['lbo'] : 0;
			$span['syncsrc'] = (isset($span['syncsrc'])) ? $span['syncsrc'] : 1;

			$spanline = "{$num},{$span['syncsrc']},{$span['lbo']},{$span['fac']}";

			if ($span['type'] != 'gsm') {
				$output[] = "span={$spanline}";
			}
			$ofxx = $this->calc_bchan_fxx($num);
			$chan = $ofxx['fxx'];
			if ( substr($span['signalling'],0,3) != 'pri' && substr($span['signalling'],0,3) != 'bri' && substr($span['signalling'],0,3) != 'gsm') {
				if (substr($span['signalling'],0,2) == 'fx') {
					$fx = str_replace('_','',$span['signalling']);
				} else if ($span['signalling'] == 'mfcr2') {
					$fx = 'mfcr2';
				} else {
					$fx = 'e&m';
				}
				$data = $span['additional_groups'];
				if (!empty($data)) {
					foreach($data as $s){
						if (strtolower($s['group'] === 's')){
							continue;
						}
						if ($span['signalling'] == 'mfcr2') {
							$chans = explode(',', $s['fxx']);
							foreach($chans as $chan){
									if(!empty($fxx[$fx])){
										$fxx[$fx] .= ',';
									}
									$fxx[$fx] .= $chan;
							}
						} else {
							$fxx[$fx] .= $s['startchan'].'-'.$s['endchan'].',';
						}
					}
					if(!empty($fxx[$fx])) {
						$fxx[$fx] = rtrim($fxx[$fx], ',');
					}
				}
			} else if (substr($span['signalling'],0,3) == 'pri' && !preg_match('/sangoma/i',$span['manufacturer'])) {
				$bchan .= ($bchan) ? ",$chan" : "$chan";
				$dchan .= ($dchan) ? ",{$span['reserved_ch']}" : "{$span['reserved_ch']}";
			} else {
				$bchan .= ($bchan) ? ",$chan" : "$chan";
				$hardhdlc .= ($hardhdlc) ? ",{$span['reserved_ch']}" : "{$span['reserved_ch']}";
			}

			$this->spans[$num]['dahdichanstring'] = $chan;
		}
		foreach ($fxx as $e=>$val) {
			if($e == "mfcr2") {
				$output[]  = "cas={$val}:1101";
			} else {
				$output[]  = "$e={$val}";
			}

			$output[]  = 'echocanceller='.$amp_conf['DAHDIECHOCAN'].','.str_replace(":1101", "", $val);
		}

		if ($bchan) {
			$output[] = "bchan={$bchan}";
		}

		if ($dchan) {
			$output[]  = "dchan={$dchan}";
		}

		if ($hardhdlc) {
			$output[]  = "hardhdlc={$hardhdlc}";
		}

		$fxols = array();
		$fxoks = array();
		$fxsls = array();
		$fxsks = array();

		foreach ($this->fxo_ports as $fxo) {
			if (in_array($fxo, $this->ports_signalling['ls'])) {
				$fxols[] = $fxo;
			} else {
				$fxoks[] = $fxo;
			}
		}

		foreach ($this->fxs_ports as $fxs) {
			if (in_array($fxs, $this->ports_signalling['ls'])) {
				$fxsls[] = $fxs;
			} else {
				$fxsks[] = $fxs;
			}
		}

		if ($fxols) {
			$channels = dahdi_array2chans($fxols);
			if($channels !== false){
				$output[] = "fxsls=".$channels;
				$output[]  = 'echocanceller='.$amp_conf['DAHDIECHOCAN'].','.$channels;
			}
		}
		if ($fxoks) {
			$channels = dahdi_array2chans($fxoks);
			if($channels !== false){
				$output[] = "fxsks=".$channels;
				$output[]  = 'echocanceller='.$amp_conf['DAHDIECHOCAN'].','.$channels;
			}
		}
		if ($fxsls) {
			$channels = dahdi_array2chans($fxsls);
			if($channels !== false){
				$output[] = "fxols=".$channels;
				$output[]  = 'echocanceller='.$amp_conf['DAHDIECHOCAN'].','.$channels;
			}
		}
		if ($fxsks) {
			$channels = dahdi_array2chans($fxsks);
			if($channels !== false){
				$output[] = "fxoks=".$channels;
				$output[]  = 'echocanceller='.$amp_conf['DAHDIECHOCAN'].','.$channels;
			}
		}

		$output[] = "loadzone={$this->systemsettings['tone_region']}";
		$output[] = "defaultzone={$this->systemsettings['tone_region']}";

		foreach($this->get_all_systemsettings() as $k => $v) {
			if(!is_array($this->original_system) || (is_array($this->original_system) && !in_array($k,$this->original_system))){
				if(empty($k)||empty($v)){continue;}
				$output[] = $k."=".$v;
			}
		}

		$output = implode(PHP_EOL, $output);

		file_put_contents($file,$this->header.$output);

		return true;
	}

	public function write_modules() {
		foreach($this->modules as $mod_name => $module) {
			if(method_exists($module,'get_filename')) {
				foreach($module->get_filename() as $file) {
					if(method_exists($module,'generateConf')) {
						$module->generateConf($file);
					}
				}
			}
		}
		return true;
	}

	public function write_dahdi_modules($settings) {
		global $amp_conf,$db;
		$file = $amp_conf['DAHDIMODULESLOC'];

		if(!empty($settings['reset'])) {
			if(!file_exists(dirname(__FILE__).'/modules.reset') || !is_readable(dirname(__FILE__).'/modules.reset')) {
				return false;
			}
			$m = file_get_contents(dirname(__FILE__).'/modules.reset');
			if(!$amp_conf['DAHDIDISABLEWRITE']) {
				file_put_contents($file, $m);
			}
			return true;
		}

		if(empty($settings['order'])) {
			return false;
		}

		$settings = $settings['order'];

		//because in the function parse_ini harsh marks are depreciated and this file uses them so I skip that function
		if(!file_exists($file) || !is_readable($file)) {
			return false;
		}
		$contents = file_get_contents($file);

		//Enable/disable modules already in the file
		$previous_module = '';
		foreach($settings as $key => $state) {
			$state = ($state == 'true') ? true : false;
			$key_split = explode('::',$key);
			$module = $key_split[1];
			$type = $key_split[0];
			switch($type) {
				case "ud":
				//see if module exists add it if it doesn't create/add it
				if(!preg_match('/^#'.$module.'$/m', $contents) && !preg_match('/^'.$module.'$/m', $contents)) {
					$contents = preg_replace('/'.$previous_module.'/', $previous_module."\n\n# UserDefined\n".$module, $contents);
				}
				//no break here as we want to run this next bit on the user defined settings as well
				case "sys":
				//dont allow broken modules to be saved here
				//exec('modprobe '.$module,$out,$return_var);
				//$state = ($return_var == '0') ? $state : false;
				if($state) {
					//make sure module is enabled, if it is then skip, if not fix it
					if(!preg_match('/^'.$module.'$/m', $contents) && preg_match('/^#'.$module.'$/m', $contents)) {
						$contents = preg_replace('/^#'.$module.'$/m', $module, $contents);
					}
				} else {
					//make sure module is disabled, if it is then skip, if not fix it
					if(!preg_match('/^#'.$module.'$/m', $contents) && preg_match('/^'.$module.'$/m', $contents)) {
						$contents = preg_replace('/^'.$module.'$/m', '#'.$module, $contents);
					}
				}
				break;
			}
			$previous_module = $module;
		}

		//Find all UserDefined Modules
		preg_match_all('/# UserDefined\n(.*)\n/',$contents,$matches,PREG_SET_ORDER);
		foreach($matches as $results) {
			$mod = str_replace('#', '', trim($results[1]));
			//If they arent in our inital array as UD then remove them from the file
			if(!isset($settings['ud::'.$mod])) {
				$contents = preg_replace('/# UserDefined\n'.$results[1].'\n/', '', $contents);
			}
		}

		//now group and order the comments with the modules. This is going to be weird.
		//We do it the digium way, we assume the comments for each module are above that module
		$lines = explode("\n",$contents);
		$groups = array();
		$i = 0;
		foreach($lines as $key => $line) {
			$line = trim($line); //trim away all whitespace surrounding each line

			//If the line below is completely emtpy
			//or if our line has no whitespaces and the next line starts with a comment
			//then we assume we are about to start a new group
			if(empty($line) || (preg_match('/\s/', $line) && preg_match('/^#/', $lines[$key+1])))
			$i++;

			if(!empty($line)) {
				$groups[$i] = isset($groups[$i]) ? $groups[$i] . $line . "\n" : $line . "\n";
			}
		}

		//Assign modules comments and modules to a group with a key with the name of the module.
		//This is for ordering
		//Also the way this is done will prevent name clashes. There will never be two modules with the same name in the file
		$i = 0;
		foreach($groups as $key => $mods) {
			$lines = explode("\n",$mods);
			$is_mod = false;
			foreach($lines as $line) {
				$line = trim($line);
				if(!preg_match('/\s/', $line) && !empty($line) && $line != '#') {
					$is_mod = true;
					$mod = str_replace('#','',$line);
					$i++;
				}
			}
			if($is_mod) {
				$ngroups[$mod] = $mods;
			}
		}

		//Now order the modules
		$file_output = '';
		foreach($settings as $key => $state) {
			$key_split = explode('::',$key);
			$module = $key_split[1];

			if(isset($ngroups[$module])) {
				$file_output .= $ngroups[$module] . "\n";
			}
		}

		if(!$amp_conf['DAHDIDISABLEWRITE']) {
			file_put_contents($file, $this->header . "\n" . $file_output);
		}

		return true;
	}

	/**
	 * Write Modprobe
	 *
	 * Write all the modprob options to modprobe.conf
	 */
	public function write_modprobe() {
		global $amp_conf;

		$dahdi_ge_260 = version_compare(dahdiconfig_getinfo('version'),'2.6.0','ge');
		$file = $amp_conf['DAHDIMODPROBELOC'];

		global $db;
		$nt = notifications::create($db);
		if ( ! is_writable($file)) {
			$nt->add_error('dahdiconfig', 'MODPROBECONF', sprintf(_('Unable to write to %s'),$file), sprintf(_("Please change permissions on %s"),$file), "", false, true);
			return false;
		} else {
			if($nt->exists('dahdiconfig', 'MODPROBECONF')) {
				$nt->delete('dahdiconfig', 'MODPROBECONF');
			}
		}

		$content = '';

		$sql = "SELECT * FROM dahdi_advanced_modules";
		$options = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);

		foreach($options as $data) {
			$settings = json_decode($data['settings'],TRUE);
			$options = "";

			$opts = array('opermode'=>'opermode', 'alawoverride'=>'alawoverride', 'boostringer'=>'boostringer', 'lowpower'=>'lowpower', 'fastringer'=>'fastringer', 'ringdetect'=>'fwringdetect', 'fxs_honor_mode'=>'fxshonormode', 'mode'=>'mode', 'defaultlinemode'=>'default_linemode');
			if($dahdi_ge_260 && $data['module_name'] != 'wctdm') {
				unset($opts['ringdetect']);
			}
			foreach ($opts as $opt=>$name) {
				if ($settings["{$opt}_checkbox"]) {
					$options .= " {$name}={$settings[$opt]}";
				}
			}

			if ($settings["mwi_checkbox"]) {
				if ($settings['mwi'] == 'neon') {
					$options .= " neonmwi_monitor=1";
					if ($settings['neon_voltage']) {
						$options .= " neonmwi_level={$settings['neon_voltage']}";
					}
					if ($settings['neon_offlimit']) {
						$options .= " neonmwi_offlimit={$settings['neon_offlimit']}";
					}
				} else {
					$options .= " neonmwi_monitor=0";
				}
			}

			$opts = array('echocan_nlp_type'=>'vpmnlptype', 'echocan_nlp_threshold'=>'vpmnlpthresh', 'echocan_nlp_max_supp'=>'vpmnlpmaxsupp');
			foreach ($opts as $adv=>$opt) {
				if ($settings[$adv]) {
					$options .= " {$opt}={$settings[$adv]}";
				}
			}

			if(isset($settings['additionals'])) {
				foreach($settings['additionals'] as $key=>$val) {
					$options .= " {$key}={$val}";
				}
			}

			$content .= !empty($options) ? "options ".$data['module_name'] .$options."\n" : '';
		}

		if(!$amp_conf['DAHDIDISABLEWRITE']) {
			file_put_contents($file, $this->header.$content);
		}

		return true;
	}
	//Mocks
	private function get_all_modules_mock(){
		return json_decode('{"wct4xxp":{"status":true,"type":"sys"},"wcte43x":{"status":true,"type":"sys"},"wcte12xp":{"status":true,"type":"sys"},"wcte13xp":{"status":true,"type":"sys"},"wct1xxp":{"status":true,"type":"sys"},"wcte11xp":{"status":true,"type":"sys"},"wctdm24xxp":{"status":true,"type":"sys"},"wcaxx":{"status":true,"type":"sys"},"wcfxo":{"status":true,"type":"sys"},"wctdm":{"status":true,"type":"sys"},"wcb4xxp":{"status":true,"type":"sys"},"wctc4xxp":{"status":true,"type":"sys"},"xpp_usb":{"status":true,"type":"sys"},"opvxd115":{"status":false,"type":"sys"},"opvxa24xx":{"status":false,"type":"sys"},"opvxa1200":{"status":false,"type":"sys"},"zaphfc":{"status":false,"type":"sys"},"tor3e":{"status":false,"type":"sys"},"r1t1":{"status":true,"type":"sys"},"rxt1":{"status":true,"type":"sys"},"rcbfx":{"status":true,"type":"sys"}}',true);
	}
	private function dahdi_scan_mock(){
		return json_decode('["[1]","active=yes","alarms=RED","description=wanpipe1 card 0","name=WPT1\/0","manufacturer=Sangoma Technologies","devicetype=B601","location=SLOT=1, BUS=7","basechan=1","totchans=24","irq=0","type=digital-T1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=B8ZS,AMI","framing_opts=ESF,D4","coding=B8ZS","framing=ESF","[2]","active=yes","alarms=OK","description=wrtdm Board 1","name=WRTDM\/0","manufacturer=Sangoma Technologies","devicetype=B601","location=SLOT=1, BUS=7","basechan=25","totchans=24","irq=0","type=analog","port=25,FXO","port=26,FXO","port=27,FXO","port=28,FXO","port=29,FXS","port=30,none","port=31,none","port=32,none","port=33,none","port=34,none","port=35,none","port=36,none","port=37,none","port=38,none","port=39,none","port=40,none","port=41,none","port=42,none","port=43,none","port=44,none","port=45,none","port=46,none","port=47,none","port=48,none","[3]","active=yes","alarms=UNCONFIGURED","description=T4XXP (PCI) Card 0 Span 1","name=TE4\/0\/1","manufacturer=Digium","devicetype=Wildcard TE420 (5th Gen)","location=Board ID Switch 0","basechan=49","totchans=31","irq=0","type=digital-E1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=AMI,HDB3","framing_opts=CCS,CRC4","coding=","framing=CAS","[4]","active=yes","alarms=UNCONFIGURED","description=T4XXP (PCI) Card 0 Span 2","name=TE4\/0\/2","manufacturer=Digium","devicetype=Wildcard TE420 (5th Gen)","location=Board ID Switch 0","basechan=80","totchans=31","irq=0","type=digital-E1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=AMI,HDB3","framing_opts=CCS,CRC4","coding=","framing=CAS","[5]","active=yes","alarms=UNCONFIGURED","description=T4XXP (PCI) Card 0 Span 3","name=TE4\/0\/3","manufacturer=Digium","devicetype=Wildcard TE420 (5th Gen)","location=Board ID Switch 0","basechan=111","totchans=31","irq=0","type=digital-E1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=AMI,HDB3","framing_opts=CCS,CRC4","coding=","framing=CAS","[6]","active=yes","alarms=UNCONFIGURED","description=T4XXP (PCI) Card 0 Span 4","name=TE4\/0\/4","manufacturer=Digium","devicetype=Wildcard TE420 (5th Gen)","location=Board ID Switch 0","basechan=142","totchans=31","irq=0","type=digital-E1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=AMI,HDB3","framing_opts=CCS,CRC4","coding=","framing=CAS","[7]","active=yes","alarms=UNCONFIGURED","description=Wildcard TE122 Card 0","name=WCT1\/0","manufacturer=Digium","devicetype=Wildcard TE122","location=PCI Bus 07 Slot 03","basechan=173","totchans=24","irq=0","type=digital-T1","syncsrc=0","lbo=0 db (CSU)\/0-133 feet (DSX-1)","coding_opts=B8ZS,AMI","framing_opts=ESF,D4","coding=","framing=CAS"]',true);
	}
}
