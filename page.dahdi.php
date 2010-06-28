<?php

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

$dahdi_cards = new dahdi_cards();

$page = $_GET['dahdi_form'];
$reboot = ((isset($_GET['reboot'])) ? $_GET['reboot'] : false );
$error = array();

/**
 * The following if statements check for when a form has been submitted. There
 * are 3 possible forms: advanced, analog, and digital (span). These conditions
 * check for each form's submit button value. If none are true, then no form
 * has been submitted. Depending on if the information and updating are
 * successful, $_GET['dahdi_form'] will be changed to reflect which page to 
 * load. Properly submitted forms that update properly will result in returning
 * to the default dahdi page. Errors with values or updating will result in
 * returning to the form page with the submitted information auto-filled.  
 */
if ($_POST['advanced_submit']) {

	$adv = array();
	foreach ($dahdi_cards->get_all_advanced() as $k=>$v) {
		if ( ! isset($_POST[$k])) {
			if (strpos($k, 'checkbox')) {
				$adv[$k] = 0;
			} else {
				$adv[$k] = $v;
			}
			continue;
		}

		$adv[$k] = $_POST[$k];
	}

	$g2g = true;
	if (preg_match('/\w\w*/', $adv['module_name']) == 0) {
		$error['module_name'] = "Module name is invalid.";
		$g2g = false;
	}

	if ($g2g) {
		$dahdi_cards->update_dahdi_advanced($adv);
		$dahdi_cards->read_dahdi_advanced();
		$dahdi_cards->write_modprobe();
		$dahdi_cards->write_system_conf();
		$page = '';
	}

} else if ($_POST['editanalog_submit']) {
	$type = $_POST['type'];

	$spans = ($type == 'fxo') ? $dahdi_cards->get_fxo_ports() : $dahdi_cards->get_fxs_ports();
	foreach ($spans as $span) {
		$port = array();
		$port['signalling'] = $_POST["port_{$span}"];
		$port['group'] = ($_POST["port_{$span}_group"])?$_POST["port_{$span}_group"]:0;
		$port['context'] = $_POST["port_{$span}_context"];
		$dahdi_cards->set_analog_signalling($span, $port);
		unset($port);
	}

	$dahdi_cards->write_analog_signalling();

	$page = '';

} else if ($_POST['editspan_submit']) {

	$editspan = array();

	$vars = array('span', 'fac', 'channels', 'signalling', 'switchtype', 'syncsrc', 'lbo', 'pridialplan', 'prilocaldialplan', 'group', 'context', 'definedchans');
	foreach ($vars as $var) {
		$editspan[$var] = $_POST['editspan_'.$var];
	}

	$dahdi_cards->update_span($editspan);


	$page = '';

} else if (isset($_POST['advanced_cancel']) || isset($_POST['editanalog_cancel']) || isset($_POST['editspan_cancel'])) {
	$page = '';
}
?>

<style type="text/css">
	label { clear: both; display: block; float: left; margin-right: 5px;  text-align: right; width: 255px; }
	th { background: #7aa8f9; } 
	tr.odd td { background: #fde9d1; } 
	.alert { background: #fde9d1; border: 2px dashed red; margin: 5px; padding: 5px; }

	<? if ($reboot): ?>
	#reboot { background: #fde9d1; border: 2px dashed red; margin: 5px; padding: 5px; }
	<? else: ?>
	#reboot { display: none; }
	<? endif; ?>

</style>
<script>
function ChangeSelectByValue(dom_id, value, change) {
	var dom = document.getElementById(dom_id);
	for (var i = 0; i < dom.options.length; i++) {
		if (dom.options[i].value == value) {
			if (dom.selectedIndex != i) {
				dom.selectedIndex = i;
				//if (change)
				//	dom.onchange();
			}
			break;
		}
	}
}
</script>

<div id="reboot">For your hardware changes to take effect, you need to reboot your system!</div>

<?
/**
 * The following switch statement determines what to render. This
 * determination is dependent on the dahdi_form variable.
 */
switch($page) {
case 'digital_span':
	if ( ! isset($_GET['span'])) { ?>
		<h1>No Span specified</h1>
	<? }
	$span = $dahdi_cards->get_span($_GET['span']);
	require 'modules/dahdiconfig/views/dahdi_digital_span_form.php';
	break;
case 'analog_signalling':
	require 'modules/dahdiconfig/views/dahdi_analog_signalling_form.php';
	break;
default:
	if ($dahdi_cards->hdwr_changes()): ?>
		<div class="alert">You have new hardware! Please configure your new hardware using the Edit button(s).</div>
	<? endif; ?>
	<div id="digital_hardware">
	<?php require 'modules/dahdiconfig/views/dahdi_digital_hardware.php'; ?>
	</div>
	<div id="analog_hardware">
	<?php require 'modules/dahdiconfig/views/dahdi_analog_hardware.php'; ?>
	</div>
	<div id="dahdi_advanced_settings">
	<?php require 'modules/dahdiconfig/views/dahdi_advanced_settings.php'; ?>
	</div>
<?php 	break;
}

