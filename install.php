<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

global $db;
global $amp_conf;
global $asterisk_conf;

if (! function_exists('out')) {
	function out ($text) {
		echo $text."<br />";
	}
}

if (! function_exists('outn')) {
	function outn ($text) {
		echo $text;
	}
}

$entries = array(
    'module_name'=>'wctdm24xxp',
    'tone_region'=>'us',
    'opermode_checkbox'=>0,
    'opermode'=>'USA',
    'alawoverride_checkbox'=>0,
    'alawoverride'=>0,
    'fxs_honor_mode_checkbox'=>0,
    'fxs_honor_mode'=>0,
    'boostringer_checkbox'=>0,
    'boostringer'=>0,
    'lowpower_checkbox'=>0,
    'lowpower'=>0,
    'fastringer_checkbox'=>0,
    'fastringer'=>0,
    'ringdetect_checkbox'=>0,
    'ringdetect'=>0,
    'mwi_checkbox'=>0,
    'mwi'=>'none',
    'neon_voltage'=>'',
    'neon_offlimit'=>'',
    'echocan_nlp_type'=>0,
    'echocan_nlp_threshold'=>'',
    'echocan_nlp_max_supp'=>''
);

foreach ($entries as $entry=>$default_val) {
    $sql = "INSERT INTO dahdi_advanced (keyword, default_val) VALUES ('{$entry}', '{$default_val}')";

	$result = $db->query($sql);
	if (DB::IsError($result)) {
		die_freepbx($result->getDebugInfo());
	}
	unset($result);
} else {
	$sql = "ALTER TABLE `dahdi_analog` CHANGE COLUMN `group` `group` VARCHAR(10) NULL DEFAULT NULL ;";
	$db->query($sql);
}

outn(_("Checking dahdi_analog_custom table..."));
$table = \FreePBX::Database()->migrate("dahdi_analog_custom");
$cols = array (
  'dahdi_analog_port' =>
  array (
    'type' => 'integer',
  ),
  'keyword' =>
  array (
    'type' => 'string',
    'length' => 50,
  ),
  'val' =>
  array (
    'type' => 'string',
    'length' => 255,
    'notnull' => false,
  ),
);

$indexes = array (
  'idx' =>
  array (
    'type' => 'unique',
    'cols' =>
    array (
      0 => 'dahdi_analog_port',
      1 => 'keyword',
    ),
  ),
);
$table->modify($cols, $indexes);
unset($table);
out(_("Done"));

if(!$db->getAll('SHOW TABLES LIKE "dahdi_configured_locations"')) {
	out(_('Create Configured Locations Table'));
	$sql = "CREATE TABLE IF NOT EXISTS dahdi_configured_locations (
		`location` VARCHAR(50),
		`device` VARCHAR(50),
		`basechan` INT,
		`type` VARCHAR(25)
	);";

    unset($result);
}


$freepbx_conf =& freepbx_conf::create();

// DAHDISHOWDIGITALCHANS in Advanced Settings of FreePBX
//
$set['value'] = false;
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 1;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'Allow PRI Discrete Channels';
$set['description'] = 'DAHDi trunk configuration is normally done using groups for PRI configuration. If there is a need to configure trunks to specific channels, setting this to true will allow each channel to be configured. This can be useful when troubleshooting a PRI and trying to isolate a bad B Channel.';
$set['type'] = CONF_TYPE_BOOL;
$freepbx_conf->define_conf_setting('DAHDISHOWDIGITALCHANS',$set,true);

// DAHDISHOWDIGITALCHANS in Advanced Settings of FreePBX
//
$set['value'] = false;
$set['defaultval'] =& $set['value'];
$set['readonly'] = 1;
$set['hidden'] = 0;
$set['level'] = 1;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'Use mock hardware instead of real hardware';
$set['description'] = 'Some development environments can not host DAHDI hardware so this mode uses mock files to acomplish the tasks.';
$set['type'] = CONF_TYPE_BOOL;
$freepbx_conf->define_conf_setting('DAHDIMOCKHW',$set,true);

$set['value'] = true;
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 1;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'Disable DAHDi Configuration Writes';
$set['description'] = 'By default the DAHDi configuration module will NOT write out any data to protect any current configuration settings';
$set['type'] = CONF_TYPE_BOOL;
$freepbx_conf->define_conf_setting('DAHDIDISABLEWRITE',$set,true);

$set['value'] = '/etc/init.d/dahdi';
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'DAHDi Executable Location';
$set['description'] = 'Location of the DAHDi Executable';
$set['type'] = CONF_TYPE_TEXT;
$freepbx_conf->define_conf_setting('DAHDIEXEC',$set,true);

$set['value'] = '/etc/modprobe.d/dahdi.conf';
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'ModProbe.d Configuration File Location';
$set['description'] = 'DAHDi ModProbe.d Configuration File Location (modprobe.d/dahdi.conf)';
$set['type'] = CONF_TYPE_TEXT;
$freepbx_conf->define_conf_setting('DAHDIMODPROBELOC',$set,true);

$set['value'] = '/etc/dahdi/system.conf';
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'System Configuration File Location';
$set['description'] = 'DAHDi System Configuration File Location (dahdi/system.conf)';
$set['type'] = CONF_TYPE_TEXT;
$freepbx_conf->define_conf_setting('DAHDISYSTEMLOC',$set,true);

$set['value'] = '/etc/dahdi/modules';
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'DAHDi Modules Location';
$set['description'] = 'DAHDi Modules Location (/etc/dahdi/modules)';
$set['type'] = CONF_TYPE_TEXT;
$freepbx_conf->define_conf_setting('DAHDIMODULESLOC',$set,true);

//echocan
//echo cancel
$set['value'] = 'oslec';
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig'; //This will help delete the settings when module is uninstalled
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'Software EC';
$set['description'] = 'software EC to use in system.conf';
$set['type'] = CONF_TYPE_TEXT;
$freepbx_conf->define_conf_setting('DAHDIECHOCAN',$set,true);

if(!$db->getAll('SHOW TABLES LIKE "dahdi_advanced_modules"')) {
	out("Creating Dahdi Advanced Modules Table");
    $sql = "CREATE TABLE IF NOT EXISTS dahdi_advanced_modules (
        `id` INT UNSIGNED NOT NULL PRIMARY KEY auto_increment,
    	`module_name` VARCHAR(100) UNIQUE,
    	`settings` BLOB
    );";
    $result = $db->query($sql);
    if (DB::IsError($result)) {
    	die_freepbx($result->getDebugInfo());
    }

	out(_("Migrating Old Data from Dahdi Advanced Table"));
    $sql = 'SELECT * FROM dahdi_advanced';
    $oldadv = sql($sql,'getAll',DB_FETCHMODE_ASSOC);

    $settings = array();
    foreach($oldadv as $data) {
        $settings[$data['keyword']] = isset($data['val']) ? $data['val'] : $data['default_val'];
        if (strpos($data['keyword'], 'checkbox')) {
            $settings[$data['keyword']] = $settings[$data['keyword']] == 1 ? TRUE : FALSE;
    	}
    }

    $module_name = $settings['module_name'];
    unset($settings['module_name']);
    unset($settings[$module_name]);

	out(_("Inserting Old Data from Dahdi Advanced Table"));
    $sql = "INSERT IGNORE INTO dahdi_advanced_modules (module_name, settings) VALUES ('".$db->escapeSimple($module_name)."', '".$db->escapeSimple(serialize($settings))."')";
    sql($sql);

	out(_("Deleting old dahdi module data from database (its been migrated)"));
	foreach ($entries as $entry=>$default_val) {
	    if($entry != 'tone_region') {
	        $sql = "DELETE FROM dahdi_advanced WHERE keyword = '".$entry."'";
	        sql($sql);
	    }
	}

	$globalsettings = array(		// global array of values
		'tone_region'=>'us',
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

	outn(_('Replacing..'));
	foreach($globalsettings as $k => $v) {
		outn('..'.$k.'..');
	    $sql = "REPLACE INTO dahdi_advanced (default_val, keyword) VALUES ('".$db->escapeSimple($v)."', '".$db->escapeSimple($k)."')";
	    sql($sql);
	}
	out(_('..Done'));
}



$mod_loc = $freepbx_conf->get_conf_setting('DAHDIMODULESLOC');

if(file_exists($mod_loc)) {
	$contents = file_get_contents($mod_loc);
	if((!preg_match('/^wcte43x/im',$contents) && !preg_match('/^#wcte43x/im',$contents))) {
		out(sprintf(_("Detected new Dahdi Module: wcte43x, Appending to %s"),basename($mod_loc)));
		$data = "\n# Digium TE435\n# Digium TE235\n#wcte43x\n";
		file_put_contents($mod_loc,$data,FILE_APPEND);
	}
	if((!preg_match('/^wcaxx/im',$contents) && !preg_match('/^#wcaxx/im',$contents))) {
		out(sprintf(_("Detected new Dahdi Module: wcaxx, Appending to %s"),basename($mod_loc)));
		$data = "\n# Digium A4A/A4B/A8A/A8B\n#wcaxx\n";
		file_put_contents($mod_loc,$data,FILE_APPEND);
	}
}

if(file_exists($amp_conf['AMPBIN']."/freepbx_engine_hook_dahdiconfig") && is_writable($amp_conf['AMPBIN']."/freepbx_engine_hook_dahdiconfig")) {
	unlink($amp_conf['AMPBIN']."/freepbx_engine_hook_dahdiconfig");
}

if(!function_exists("dahdi_config2array")) {
	include __DIR__."/functions.inc.php";
	$dahdi_cards = new \dahdi_cards();
	$dahdi_cards->checkHardware();
}
