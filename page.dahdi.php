<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
global $astman;
$o = $astman->send_request('Command', array('Command' => 'dahdi show version'));
if (isset($_POST['restartdahdi'])) {
    exec('asterisk -rx "module unload chan_dahdi.so"');
    exec('asterisk -rx "module load chan_dahdi.so"');
    $astman->send_request('Command', array('Command' => 'dahdi restart'));
}

//Get dahdi version
$dahdi_info = explode("\n",$o['data']);

//Check to make sure dahdi is running. Display an error if it's not
if(!preg_match('/\d/i',$dahdi_info[1])) {
    $dahdi_message = 'DAHDi Doesn\'t appear to be running. Click the \'Restart/Reload Dahdi Button\' Below';
    include('views/dahdi_message_box.php');
    $dahdi_info[1] = '';
} elseif(!preg_match('/\d/i',$dahdi_info[1]) && isset($_POST['restartdahdi'])) {
    exec('asterisk -rx "module unload chan_dahdi.so"');
    exec('asterisk -rx "module load chan_dahdi.so"');
    $astman->send_request('Command', array('Command' => 'dahdi restart'));
}

//Check to make sure we aren't symlinking chan_dahdi.conf like we were in the past as we don't do that anymore.
if(is_link('/etc/asterisk/chan_dahdi.conf') && (readlink('/etc/asterisk/chan_dahdi.conf') == dirname(__FILE__).'/etc/chan_dahdi.conf')) {
    if(!unlink('/etc/asterisk/chan_dahdi.conf')) {
        //If unlink fails then alert the user
        $dahdi_message = 'Please Delete the System Generated /etc/asterisk/chan_dahdi.conf';
        include('views/dahdi_message_box.php');        
    }
}

$dahdi_cards = new dahdi_cards();
$error = array();

if ($dahdi_cards->hdwr_changes()) { 
	$dahdi_message = 'You have new hardware! Please configure your new hardware using the Edit button(s). Then reload DAHDI with the button below.';
    include('views/dahdi_message_box.php');
    if(file_exists($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf')) {
        global $astman;
        copy($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf', $amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf.bak');
        file_put_contents($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf', '');
        exec('asterisk -rx "module unload chan_dahdi.so"');
        exec('asterisk -rx "module load chan_dahdi.so"');
        $astman->send_request('Command', array('Command' => 'dahdi restart'));
    }
}
?>
<div id="reboot_mp" style="display:none;background-color:#f8f8ff; border: 1px solid #aaaaff; padding:10px;font-family:arial;color:red;font-size:20px;text-align:center;font-weight:bolder;">For your hardware changes to take effect, you need to reboot your system!</div>
<div id="reboot" style="display:none;background-color:#f8f8ff; border: 1px solid #aaaaff; padding:10px;font-family:arial;color:red;font-size:20px;text-align:center;font-weight:bolder;">For your changes to take effect, click the 'Restart/Reload Dahdi Button' Below</div>

<script type="text/javascript" src="assets/dahdiconfig/js/jquery.form.js"></script>
        <!-- right side menu -->
       	<div class="rnav">
       		<ul>
       			<a style="text-decoration:underline"><strong>Settings</strong></a><br />
       			<a href="#" onclick="dahdi_modal_settings('global');">Global Settings</a><br />
       			<a href="#" onclick="dahdi_modal_settings('modprobe');">Modprobe Settings</a><br />
       		</ul>
       	</div>
       	
       	<div id="global-settings" title="Global Settings" style="display: none;">
            <?php require dirname(__FILE__).'/views/dahdi_global_settings.php'; ?>
        </div>
       	<div id="modprobe-settings" title="Modprobe Settings" style="display: none;">
            <?php require dirname(__FILE__).'/views/dahdi_modprobe_settings.php'; ?>
        </div>
        <?php foreach($dahdi_cards->get_spans() as $key=>$span) { 
            $span['signalling'] = !empty($span['signalling']) ? $span['signalling'] : '';
            $span['switchtype'] = !empty($span['switchtype']) ? $span['switchtype'] : '';
            $span['pridialplan'] = !empty($span['pridialplan']) ? $span['pridialplan'] : '';
            $span['prilocaldialplan'] = !empty($span['prilocaldialplan']) ? $span['prilocaldialplan'] : '';
            $span['priexclusive'] = !empty($span['priexclusive']) ? $span['priexclusive'] : '';
            ?>
        <div id="digital-settings-<?php echo $key;?>" title="Span: <?php echo $span['description']?>" style="display: none;">
            <?php require dirname(__FILE__).'/views/dahdi_digital_settings.php'; ?>
        </div>
        <?php } ?>    
        <div id="analog-settings-fxo" title="FXO Settings" style="display: none;">
            <?php $analog_type = 'fxo'; require dirname(__FILE__).'/views/dahdi_analog_settings.php'; ?>
        </div>
        <div id="analog-settings-fxs" title="FXS Settings" style="display: none;">
            <?php $analog_type = 'fxs'; require dirname(__FILE__).'/views/dahdi_analog_settings.php'; ?>
        </div>
	<div id="digital_hardware">
	<?php require dirname(__FILE__).'/views/dahdi_digital_hardware.php'; ?>
	</div>
	<div id="analog_hardware">
	<?php require dirname(__FILE__).'/views/dahdi_analog_hardware.php'; ?>
	</div>
	<div class="btn_container">
	    <form name="dahdi_advanced_settings" method="post" action="config.php?display=dahdi">
    	    <input type="submit" id="restartdahdi" name="restartdahdi" value="Restart/Reload Dahdi" />
    	</form>
    </div>
<h5><?php echo $dahdi_info[1];?></h5>

<script>
var dgps = new Array();
var spandata = new Array();

var modprobesettings = {}
<?php foreach($dahdi_cards->read_all_dahdi_modprobe() as $list) { ?>
modprobesettings['<?php echo $list['module_name'] ?>'] = {}
modprobesettings['<?php echo $list['module_name'] ?>']['dbsettings'] = <?php echo $list['settings'] ?>

<?php } ?>

<?php foreach($dahdi_cards->get_spans() as $key=>$span) { 
    $o = $span;
    unset($o['additional_groups']);
    $o = json_encode($o);
    ?>

spandata[<?php echo $key?>] = {};
spandata[<?php echo $key?>]['groups'] = <?php echo $span['additional_groups']?>;
spandata[<?php echo $key?>]['spandata'] = <?php echo $o?>;

$('#editspan_<?php echo $key?>_signalling').change(function() {
    if(($(this).val() == 'pri_net') || ($(this).val() == 'pri_cpe')) {
        $('#editspan_<?php echo $key?>_reserved_ch').fadeIn('slow');
    } else {
        $('#editspan_<?php echo $key?>_reserved_ch').fadeOut('slow');
    }
});

<?php $groups = json_decode($span['additional_groups'],TRUE); 
    foreach($groups as $gkey => $data) { ?>
$('#editspan_<?php echo $key?>_definedchans_<?php echo $gkey?>').change(function() {
    var span = <?php echo $key?>;
    var endchan = $(this).val();
    var totalchan = <?php echo $span['totchans']?>;
    var group = <?php echo $gkey?>;
    update_digital_groups(span,group,endchan);
});

<?php } ?> 

<?php } ?> 
$(function(){    
    //On Focus of module name element then we save the local storage
    $('#module_name').focus(function () { 
        //Local Storage is an object {}
        var settings = {'mp_setting_add':[]};
        var z = 0;
        //Find ALL elements in modprobe id.
        $("#modprobe").find('*').each(function() {
            //Store jquery data in child
            var child = $(this);
            //Following check to make sure they or form elements
            if (child.is(":checkbox"))
                settings[child.attr("name")] = child.attr("checked") ? true : false;
            if (child.is(":text"))
                settings[child.attr("name")] = child.val();
            if (child.is("select"))
                settings[child.attr("name")] = child.val();
            if (child.is(":input:hidden") && child.attr("name") == 'mp_setting_add[]') {
                settings['mp_setting_add'][z] = child.val();
                z++
            }
        })

        if(!modprobesettings.hasOwnProperty($(this).val())) {
            modprobesettings[$(this).val()] = {}
        }
        modprobesettings[$(this).val()]['formsettings'] = settings;
    }).change(function() {
        //If there is no session data then pull from database
        if(!modprobesettings.hasOwnProperty($(this).val()) || !modprobesettings[$(this).val()].hasOwnProperty('formsettings')) {
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{dcmodule: $(this).val(), type: 'modprobe'}, function(j){
                if(j.status) {
                    $('.mp_js_additionals').remove();
                    $('#mp_setting_key_0').val('')
                    $('#mp_setting_value_0').val('')
                    $('#mp_setting_origsetting_key_0').val('')
                    
                    if(j.module == "wctc4xxp") { 
                        $('#normal_mp_settings').hide();
                        $('#wctc4xxp_settings').show();
                        $("#mode_checkbox").attr('checked',j.mode_checkbox);
                        $('#mode').val(j.mode);
                    } else {
                        $('#normal_mp_settings').show();
                        $('#wctc4xxp_settings').hide();
                        $("#opermode_checkbox").attr('checked',j.opermode_checkbox);
                        $('#opermode').val(j.opermode);
                        $("#alawoverride_checkbox").attr('checked',j.alawoverride_checkbox);
                        $('#alawoverride').val(j.alawoverride);
                        $('#fxs_honor_mode_checkbox').attr('checked',j.fxs_honor_mode_checkbox);
                        $('#fxs_honor_mode').val(j.fxs_honor_mode);
                        $('#boostringer_checkbox').attr('checked',j.boostringer_checkbox);
                        $('#boostringer').val(j.boostringer);
                        $('#fastringer_checkbox').attr('checked',j.fastringer_checkbox);
                        $('#fastringer').val(j.fastringer);
                        $('#lowpower_checkbox').attr('checked',j.lowpower_checkbox);
                        $('#lowpower').val(j.lowpower);
                        $('#ringdetect_checkbox').attr('checked',j.ringdetect_checkbox);
                        $('#ringdetect').val(j.ringdetect);
                        $('#mwi_checkbox').attr('checked',j.mwi_checkbox);
                        $('#mwi').val(j.mwi);
                        if (j.mwi == 'neon') {
                            $('.neon').show();
                        } else {
                            $('.neon').hide();
                        }
                        $('#neon_voltage').val(j.neon_voltage);
                        $('#neon_offlimit').val(j.neon_offlimit);
                    }
                    
                    //Re-create additionals for this probe
                    var z = 1;
                    if(typeof j.additionals !== 'undefined') {
                        $.each(j.additionals, function(index, value) {
                            if(z == 1) {
                                $('#mp_setting_key_0').val(index)
                                $('#mp_setting_value_0').val(value)
                            } else {
                                $("#mp_add").before('<tr class="mp_js_additionals" id="mp_additional_'+z+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><a href="#" onclick="mp_delete_field('+z+',\''+j.module+'\')"><img height="10px" src="images/trash.png"></a> <input type="hidden" name="mp_setting_add[]" value="'+z+'" /><input type="hidden" id="mp_setting_origsetting_key_'+z+'" name="mp_setting_origsetting_key_'+z+'" value="'+index+'" /> <input id="mp_setting_key_'+z+'" name="mp_setting_key_'+z+'" value="'+index+'" /> = <input id="mp_setting_value_'+z+'" name="mp_setting_value_'+z+'" value="'+value+'" /> <br /></td></tr>');
                            }
                            z++  
                        })
                    }
                    $("#mp_add_button").attr("onclick","mp_add_field("+z+",'"+j.module+"')");
                }
            })
        } else {
            //Hide neon settings
            $('.neon').hide();
            //Remove all extra additionals
            $('.mp_js_additionals').remove();
            var module = $(this).val();
            //Re-create additionals for this probe
            var z = 1;
            $.each(modprobesettings[$(this).val()]['formsettings']['mp_setting_add'], function(index, value) {
                var i = value;
                if(i != '0') {
                    $("#mp_add").before('<tr class="mp_js_additionals" id="mp_additional_'+i+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><a href="#" onclick="mp_delete_field('+i+',\''+module+'\')"><img height="10px" src="images/trash.png"></a> <input type="hidden" name="mp_setting_add[]" value="'+i+'" /> <input id="mp_setting_key_'+i+'" name="mp_setting_key_'+i+'" value="" /> = <input id="mp_setting_value_'+i+'" name="mp_setting_value_'+i+'" value="" /> <br /></td></tr>');
                }
                z++
            })
            $("#mp_add_button").attr("onclick","mp_add_field("+z+",'"+module+"')");
            $.each(modprobesettings[$(this).val()]['formsettings'], function(index, value) { 
                //Check to make sure ID exits before we reset it, but only do it inside the modprobe div element (though IDs should be unique!)
              if (document.getElementById(index)) {
                  element = $('#modprobe #'+index);
                  if (element.is(":checkbox")) {
                      if(value) {
                        element.attr('checked','checked');
                    } else {
                        element.removeAttr('checked');
                    }
                  }
                  if (element.is(":text")) {
                      element.val(value);
                  }
                      
                  if (element.is("select"))
                      element.val(value);
                    //Show extra neon stuff
                    if ((index == 'mwi') && (value == 'neon')) {
                        $('.neon').show();
                    }
              }
            });
        }
    })
    
    var options = { 
        type: 'POST'
    };
    $( "#analog-settings-fxo" ).dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        buttons: {
            "Save": function() {
                $("#dahdi_editanalog_fxo").ajaxSubmit();
                $("#reboot").fadeIn(3000).show();
                toggle_reload_button('show');
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    $( "#analog-settings-fxs" ).dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        buttons: {
            "Save": function() {
                $("#dahdi_editanalog_fxs").ajaxSubmit();
                $("#reboot").fadeIn(3000).show();
                toggle_reload_button('show');
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    <?php foreach($dahdi_cards->get_spans() as $key=>$span) { ?>
    $( "#digital-settings-<?php echo $key?>" ).dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        buttons: {
            "Save": function() {
                //spandata[<?php echo $key?>]
                gdata = JSON.stringify(spandata[<?php echo $key?>]['groups'])
                $("#dahdi_editspan_<?php echo $key?>").ajaxSubmit({data: {groupdata: gdata}, dataType: 'json', success: function(j) { 
                        if(j.status) {
                            $.each(j, function(index, value) {
                                $("#digital_"+index+"_"+j.span+"_label").html(value);
                            });
                            toggle_reload_button('show');
                            $("#reboot").show();
                        }
                    }});
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    <?php } ?>
    $( "#global-settings" ).dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        buttons: {
            "Save": function() {
                $("#form-globalsettings").ajaxSubmit(options);
                toggle_reload_button('show');
                $("#reboot").fadeIn(3000).show();
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    $( "#modprobe-settings" ).dialog({
        autoOpen: false,
        height: 400,
        width: 530,
        modal: true,
        buttons: {
            "Save": function() {
                //Local Storage is an object {}
                var settings = {'mp_setting_add':[]};
                var z = 0;
                //Find ALL elements in modprobe id.
                $("#modprobe").find('*').each(function() {
                    //Store jquery data in child
                    var child = $(this);
                    //Following check to make sure they are form elements
                    if (child.is(":checkbox"))
                        settings[child.attr("name")] = child.attr("checked") ? true : false;
                    if (child.is(":text"))
                        settings[child.attr("name")] = child.val();
                    if (child.is("select"))
                        settings[child.attr("name")] = child.val();
                    if (child.is(":input:hidden") && child.attr("name") == 'mp_setting_add[]') {
                        settings['mp_setting_add'][z] = child.val();
                        z++
                    }
                })
                //Store data in our storage array
                if(!modprobesettings.hasOwnProperty($('#module_name').val())) {
                    modprobesettings[$('#module_name').val()] = {}
                }
                modprobesettings[$('#module_name').val()]['formsettings'] = settings
                $.each(modprobesettings, function(index, value) {  
                    $.post("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php&type=modprobesubmit",{settings: JSON.stringify(value['formsettings'])}, function(j){
                    
                    })
                })
                toggle_reload_button('show');
                $("#reboot_mp").fadeIn(3000).show();
                $( this ).dialog( "close" );
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
        }
    });
    
 })
$('#mwi').change(function(evt) {
	if ($('#mwi :selected').val() == 'neon') {
		$('.neon').show();
	} else {
		$('.neon').hide();
	}
});

</script>
<?php
//Easy Form Setting method
function set_default($default,$option=NULL,$true='selected') {
	if(isset($option)) {
		return $option == $default ? $true : '';
	} else {
		return isset($default) ? $default : '';
	}
}