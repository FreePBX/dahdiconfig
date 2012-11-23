<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

/**
 * FreePBX DAHDi Config Module
 *
 * Copyright (c) 2009, Digium, Inc.
 *
 * Author: Ryan Brindley <ryan@digium.com>
 *
 * This program is free software, distributed under the terms of
 * the GNU General Public License Version 2. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

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

$sql = "CREATE TABLE IF NOT EXISTS dahdi_spans (
	`id` INT UNSIGNED NOT NULL PRIMARY KEY auto_increment,
	`span` INT UNSIGNED NOT NULL,
	`active` BOOL DEFAULT 1,
	`alarms` VARCHAR(15),
	`basechan` INT UNSIGNED,
	`coding` VARCHAR(10),
	`coding_opts` VARCHAR(255),
	`context` VARCHAR(255),
	`definedchans` INT UNSIGNED,
	`description` VARCHAR (255),
	`devicetype` VARCHAR(255),
	`framing` VARCHAR(10),
	`framing_opts` VARCHAR(255),
	`group` INT UNSIGNED,
	`irq` VARCHAR(10),
	`lbo` INT UNSIGNED,
	`location` VARCHAR(255),
	`name` VARCHAR(25),
	`manufacturer` VARCHAR (25) DEFAULT 'Digium',
	`max_ch` INT UNSIGNED,
	`min_ch` INT UNSIGNED,
	`pridialplan` VARCHAR(25),
	`prilocaldialplan` VARCHAR(25),
	`reserved_ch` INT UNSIGNED,
	`signalling` VARCHAR(50),
	`spantype` VARCHAR(10),
	`switchtype` VARCHAR(50),
	`syncsrc` INT UNSIGNED,
	`timing` INT UNSIGNED,
	`totchans` INT UNSIGNED,
	`type` VARCHAR(25)
);";

$result = $db->query($sql);
if (DB::IsError($result)) {
	die_freepbx($result->getDebugInfo());
}
unset($result);

$sql = "CREATE TABLE IF NOT EXISTS dahdi_analog (
	`port` INT UNIQUE,
	`type` ENUM ('fxo', 'fxs'),
	`signalling` ENUM ('ks', 'ls'),
	`group` INT UNSIGNED,
	`context` VARCHAR(255)
);";

$result = $db->query($sql);
if (DB::IsError($result)) {
	die_freepbx($result->getDebugInfo());
}
unset($result);

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

$freepbx_conf =& freepbx_conf::create();

// DAHDISHOWDIGITALCHANS
//
$set['value'] = false;
$set['defaultval'] =& $set['value'];
$set['readonly'] = 0;
$set['hidden'] = 0;
$set['level'] = 0;
$set['module'] = 'dahdiconfig';
$set['category'] = 'DAHDi Configuration Module';
$set['emptyok'] = 0;
$set['name'] = 'Allow PRI Discrete Channels';
$set['description'] = 'DAHDi trunk configuration is normally done using groups for PRI configuration. If there is a need to configure trunks to specific channels, setting this to true will allow each channel to be configured. This can be useful when troubleshooting a PRI and trying to isolate a bad B Channel.';
$set['type'] = CONF_TYPE_BOOL;
$freepbx_conf->define_conf_setting('DAHDISHOWDIGITALCHANS',$set,true);

$sql = "CREATE TABLE IF NOT EXISTS dahdi_advanced_modules (
    `id` INT UNSIGNED NOT NULL PRIMARY KEY auto_increment,
	`module_name` VARCHAR(100) UNIQUE,
	`settings` BLOB
);";
$result = $db->query($sql);
if (DB::IsError($result)) {
	die_freepbx($result->getDebugInfo());
}

$sql = 'SELECT * FROM dahdi_advanced';

$oldadv = sql($sql,'getAll',DB_FETCHMODE_ASSOC);

$settings = array();
foreach($oldadv as $data) {
    $settings[$data['keyword']] = isset($data['val']) ? $data['val'] : $data['default_val'];
}

$module_name = $settings['module_name'];
unset($settings['module_name']);
unset($settings[$module_name]);

$sql = "INSERT IGNORE INTO dahdi_advanced_modules (module_name, settings) VALUES ('".mysql_real_escape_string($module_name)."', '".mysql_real_escape_string(serialize($settings))."')";
sql($sql);

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
    
foreach($globalsettings as $k => $v) {
    $sql = "REPLACE INTO dahdi_advanced (default_val, keyword) VALUES ('".mysql_real_escape_string($v)."', '".mysql_real_escape_string($k)."')";
    sql($sql);
}

foreach ($entries as $entry=>$default_val) {
    $sql = "DELETE FROM dahdi_advanced WHERE keyword = '".$entry."'";
    sql($sql);
}


//end of file