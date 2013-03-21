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
global $asterisk_conf;

out('Remove all dahdi tables');
$tables = array('dahdi_advanced', 'dahdi_configured_locations', 'dahdi_spans', 'dahdi_scans', 'dahdi_analog', 'dahdi_advanced_modules', 'dahdi_modules');
foreach ($tables as $table) {
	$sql = "DROP TABLE IF EXISTS {$table}";
	$result = $db->query($sql);
	if (DB::IsError($result)) {
		die_freepbx($result->getDebugInfo());
	}
	unset($result);
}

out('Remove FreePBX Advanced Setting');
//Remove FreePBX Advanced Setting
$freepbx_conf =& freepbx_conf::create();
$freepbx_conf->remove_conf_settings('DAHDISHOWDIGITALCHANS');
$freepbx_conf->remove_conf_settings('DAHDIDISABLEWRITE');