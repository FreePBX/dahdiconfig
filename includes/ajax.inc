<?php
require dirname(__FILE__).'/../functions.inc.php';
$dahdi_cards = new dahdi_cards();


switch($_REQUEST['type']) {
    case "modprobe":
        $sql = "SELECT settings FROM dahdi_advanced_modules WHERE module_name = '".mysql_real_escape_string($_REQUEST['dcmodule'])."'";
        $settings = sql($sql, 'getOne');
        if($settings) {
            $settings = unserialize($settings);

            $json = array(
                "status" => true, 
                "module" => $_REQUEST['dcmodule']
                );
            $json = array_merge($settings,$json);
        } else {
            $json = array(
                "status" => true, 
                "module" => $_REQUEST['dcmodule'],
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
        		'echocan_nlp_max_supp'=>'' 
        		);

        }
        break;
    default:
        $json = array("status" => false);
        break;
}

echo json_encode($json);