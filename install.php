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

if(!$db->getAll('SHOW TABLES LIKE "dahdi_advanced"')) {
	out(_('Creating Dahdi Advanced Settings Table'));
	$sql = "CREATE TABLE IF NOT EXISTS dahdi_advanced (
		`keyword` VARCHAR(50) NOT NULL PRIMARY KEY,
		`val` VARCHAR(255),
		`default_val` VARCHAR(255)
	);";

	$result = $db->query($sql);
	if (DB::IsError($result)) {
		die_freepbx($result->getDebugInfo());
	}
	unset($result);

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
			unset($result);
			continue;
		}

		unset($result);
	}
}

outn(_("Checking tables..."));
$table = FreePBX::Database()->migrate("dahdi_spans");
$cols = array(
	"id" => array(
		"type" => "integer",
		"autoincrement" => true,
		"primaryKey" => true
	),
	"span" => array(
		"type" => "integer",
		"notnull" => true
	),
	"active" => array(
		"type" => "boolean",
		"default" => 1,
		"notnull" => false
	),
	"alarms" => array(
		"type" => "string",
		"length" => 15,
		"notnull" => false
	),
	"basechan" => array(
		"type" => "integer",
		"notnull" => false
	),
	"coding" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false
	),
	"coding_opts" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"context" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"definedchans" => array(
		"type" => "integer",
		"notnull" => false
	),
	"description" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"devicetype" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"framing" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false
	),
	"framing_opts" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"group" => array(
		"type" => "integer",
		"notnull" => false
	),
	"irq" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false
	),
	"lbo" => array(
		"type" => "integer",
		"notnull" => false
	),
	"location" => array(
		"type" => "string",
		"length" => 255,
		"notnull" => false
	),
	"name" => array(
		"type" => "string",
		"length" => 25,
		"notnull" => false
	),
	"manufacturer" => array(
		"type" => "string",
		"length" => 25,
		"default" => "Digium"
	),
	"max_ch" => array(
		"type" => "integer",
		"notnull" => false
	),
	"min_ch" => array(
		"type" => "integer",
		"notnull" => false
	),
	"pridialplan" => array(
		"type" => "string",
		"length" => 25,
		"notnull" => false
	),
	"prilocaldialplan" => array(
		"type" => "string",
		"length" => 25,
		"notnull" => false
	),
	"reserved_ch" => array(
		"type" => "integer",
		"notnull" => false
	),
	"signalling" => array(
		"type" => "string",
		"length" => 50,
		"notnull" => false
	),
	"spantype" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false
	),
	"switchtype" => array(
		"type" => "string",
		"length" => 50,
		"notnull" => false
	),
	"syncsrc" => array(
		"type" => "integer",
		"notnull" => false
	),
	"timing" => array(
		"type" => "integer",
		"notnull" => false
	),
	"totchans" => array(
		"type" => "integer",
		"notnull" => false
	),
	"type" => array(
		"type" => "string",
		"length" => 25,
		"notnull" => false
	),
	"priexclusive" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true
	),
	"additional_groups" => array(
		"type" => "blob",
		"notnull" => false
	),
	"txgain" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => true,
		"default" => "0.0"
	),
	"rxgain" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => true,
		"default" => "0.0"
	),
	"mfcr2_variant" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "ITU"
	),
	"mfcr2_get_ani_first" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_max_ani" => array(
		"type" => "smallint",
		"notnull" => true,
		"default" => 10
	),
	"mfcr2_max_dnis" => array(
		"type" => "smallint",
		"notnull" => true,
		"default" => 4
	),
	"mfcr2_category" => array(
		"type" => "string",
		"length" => 50,
		"notnull" => true,
		"default" => "national_subscriber"
	),
	"mfcr2_call_files" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "yes"
	),
	"mfcr2_skip_category" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_logdir" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false,
	),
	"mfcr2_logging" => array(
		"type" => "string",
		"length" => 10,
		"notnull" => false,
	),
	"mfcr2_mfback_timeout" => array(
		"type" => "decimal",
		"notnull" => true,
	),
	"mfcr2_mfback_pulse_timeout" => array(
		"type" => "decimal",
		"notnull" => true,
	),
	"mfcr2_metering_pulse_timeout" => array(
		"type" => "decimal",
		"notnull" => true,
	),
	"mfcr2_allow_collect_calls" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_double_answer" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_immediate_accept" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_forced_release" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_charge_calls" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "yes"
	),
	"mfcr2_accept_on_offer" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "yes"
	),
	"mfcr2_skip_catefory" => array(
		"type" => "string",
		"length" => 3,
		"notnull" => true,
		"default" => "no"
	),
	"mfcr2_advanced_protocol_file" => array(
		"type" => "string",
		"length" => 100,
		"notnull" => false
	),
);
$table->modify($cols,array());
out(_("Done"));

if(!$db->getAll('SHOW TABLES LIKE "dahdi_analog"')) {
	out(_('Creating Dahdi Analog Table'));
	$sql = "CREATE TABLE IF NOT EXISTS dahdi_analog (
		`port` INT UNIQUE,
		`type` ENUM ('fxo', 'fxs'),
		`signalling` ENUM ('ks', 'ls'),
		`group` VARCHAR(10),
		`context` VARCHAR(255)
	);";

	$result = $db->query($sql);
	if (DB::IsError($result)) {
		die_freepbx($result->getDebugInfo());
	}
	unset($result);
} else {
	$sql = "ALTER TABLE `dahdi_analog` CHANGE COLUMN `group` `group` VARCHAR(10) NULL DEFAULT NULL ;";
	$db->query($sql);
}

if(!$db->getAll('SHOW TABLES LIKE "dahdi_configured_locations"')) {
	out(_('Create Configured Locations Table'));
	$sql = "CREATE TABLE IF NOT EXISTS dahdi_configured_locations (
		`location` VARCHAR(50),
		`device` VARCHAR(50),
		`basechan` INT,
		`type` VARCHAR(25)
	);";

	$result = $db->query($sql);
	if (DB::IsError($result)) {
		die_freepbx($result->getDebugInfo());
	}
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


if (!$db->getAll('SHOW COLUMNS FROM dahdi_spans WHERE FIELD = "reserved_ch"')) {
    if (!$db->getAll('SHOW COLUMNS FROM dahdi_spans WHERE FIELD = "dchannel"')) {
		out(_("Moving/Adding dchannel column"));
        $sql = "ALTER TABLE `dahdi_spans` ADD COlUMN `dchannel` int (5) NOT NULL DEFAULT '0'";
        $result = $db->query($sql);
    }

    $sql = "ALTER TABLE `dahdi_spans` change `dchannel` `reserved_ch`  int (5) NOT NULL DEFAULT '0";
    $result = $db->query($sql);
}

$sql = "SELECT module_name, settings FROM dahdi_advanced_modules";
$old = sql($sql,'getAll',DB_FETCHMODE_ASSOC);
foreach($old as $list) {
	if(unserialize($list['settings']) !== FALSE) {
		out(sprintf(_("Migrating module %s from serialized data to json"),$list['module_name']));
	    $o = json_encode(unserialize($list['settings']));
	    $sql = "REPLACE INTO dahdi_advanced_modules (module_name, settings) VALUES ('".$db->escapeSimple($list['module_name'])."', '".$db->escapeSimple($o)."')";
	    sql($sql);
	}
}

if(!$db->getAll('SHOW TABLES LIKE "dahdi_modules"')) {
	out(_('Creating dahdi modules Table'));
	$sql = "CREATE TABLE IF NOT EXISTS dahdi_modules (
		`module_name` VARCHAR(100) UNIQUE,
		`settings` BLOB
	);";
	sql($sql);
}

if (!$db->getAll('SHOW COLUMNS FROM dahdi_advanced WHERE FIELD = "type"')) {
	out(_("Add type column"));
	sql('ALTER TABLE dahdi_advanced ADD type varchar(50) default "chandahdi"');

	sql('UPDATE dahdi_advanced SET type="system" WHERE keyword="tone_region"');
}

if (!$db->getAll('SHOW COLUMNS FROM dahdi_advanced WHERE FIELD = "additional"')) {
	out(_("add additional column"));
	sql('ALTER TABLE dahdi_advanced ADD additional bool default 1');

	foreach($globalsettings as $ksettings => $settings) {
	    sql('UPDATE dahdi_advanced SET additional=0 WHERE keyword="'.$ksettings.'"');
	}
}

if (!$db->getAll('SHOW COLUMNS FROM dahdi_analog WHERE FIELD = "txgain"')) {
	out(_("Adding txgain and rxgain column to analog table"));
    $sql = "ALTER TABLE `dahdi_analog` ADD COlUMN `txgain` varchar (10) NOT NULL DEFAULT '0.0'";
    $result = $db->query($sql);
    $sql = "ALTER TABLE `dahdi_analog` ADD COlUMN `rxgain` varchar (10) NOT NULL DEFAULT '0.0'";
    $result = $db->query($sql);
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
