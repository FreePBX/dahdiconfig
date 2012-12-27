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
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

global $astman;
//Get dahdi version
$o = $astman->send_request('Command', array('Command' => 'dahdi show version'));
$dahdi_info = explode("\n",$o['data']);

//Check to make sure dahdi is running. Display an error if it's not
if(!preg_match('/\d/i',$dahdi_info[1])) {
    $dahdi_message = 'DAHDi Doesn\'t appear to be running. Click the \'Restart/Reload Dahdi Button\' Below';
    include('views/dahdi_message_box.php');
    $dahdi_info[1] = '';
} elseif(!preg_match('/\d/i',$dahdi_info[1]) && isset($_POST['restartdahdi'])) {
    
}
?>
<div id="reboot_mp" style="display:none;background-color:#f8f8ff; border: 1px solid #aaaaff; padding:10px;font-family:arial;color:red;font-size:20px;text-align:center;font-weight:bolder;">For your hardware changes to take effect, you need to reboot your system!</div>
<div id="reboot" style="display:none;background-color:#f8f8ff; border: 1px solid #aaaaff; padding:10px;font-family:arial;color:red;font-size:20px;text-align:center;font-weight:bolder;">For your changes to take effect, click the 'Restart/Reload Dahdi Button' Below</div>

<script type="text/javascript" src="assets/dahdiconfig/js/jquery.form.js"></script>
<?php
if(is_link('/etc/asterisk/chan_dahdi.conf') && (readlink('/etc/asterisk/chan_dahdi.conf') == dirname(__FILE__).'/etc/chan_dahdi.conf')) {
    if(!unlink('/etc/asterisk/chan_dahdi.conf')) {
        $dahdi_message = 'Please Delete the System Generated /etc/asterisk/chan_dahdi.conf';
        include('views/dahdi_message_box.php');        
    }
}

$dahdi_cards = new dahdi_cards();
$error = array();

if (isset($_POST['restartdahdi'])) {
    //dahdi restart
    global $astman;
    $astman->send_request('Command', array('Command' => 'module reload chan_dahdi.so'));
    $astman->send_request('Command', array('Command' => 'dahdi restart'));
}
?>
<style type="text/css">
    label {
        width:160px;    /*Or however much space you need for the form’s labels*/
        float:left;
    }
	th { background: #7aa8f9; } 
	tr.odd td { background: #fde9d1; } 
	.alert { background: #fde9d1; border: 2px dashed red; margin: 5px; padding: 5px; }
</style>
<script>
    function dahdi_modal_settings(type,id) {
        if(typeof id !== 'undefined') {
            $( "#"+type+"-settings-"+id ).dialog( "open" );
        } else {
            $( "#"+type+"-settings" ).dialog( "open" );
        }
    }
</script>

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
        <?php foreach($dahdi_cards->get_spans() as $key=>$span) { ?>
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

    <?php
	if ($dahdi_cards->hdwr_changes()) { 
		$dahdi_message = 'You have new hardware! Please configure your new hardware using the Edit button(s). Then reload DAHDI with the button below.';
        include('views/dahdi_message_box.php');
        if(file_exists($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf')) {
            global $astman;
            copy($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf', $amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf.bak');
            file_put_contents($amp_conf['ASTETCDIR'].'/chan_dahdi_groups.conf', '');
            $astman->send_request('Command', array('Command' => 'module reload chan_dahdi.so'));
            $astman->send_request('Command', array('Command' => 'dahdi restart'));
        }
    } ?>
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

/* Span Group Automation */
function update_digital_groups(span,group,usedchans) {
    usedchans = Number(usedchans)
    span = Number(span)
    group = Number(group)
    
    spandata[span]['groups'][group]['usedchans'] = Number(usedchans)
    
    $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'calcbchanfxx', span: span, usedchans: usedchans, startchan: spandata[span]['groups'][group]['startchan']}, function(j){
        j.endchan = Number(j.endchan)
        $('#editspan_'+span+'_from_'+ group).html(j.fxx);
        spandata[span]['groups'][group]['endchan'] = j.endchan;
        spandata[span]['groups'][group]['fxx'] = j.fxx
        spandata[span]['groups'][group]['span'] = j.span
        
        if(j.endchan < spandata[span]['spandata']['max_ch']) {
            if (!document.getElementById('editspan_'+span+'_group_settings_' + (group+1))) {
                var startchan = j.endchan+1
                var add = ((spandata[span]['groups'][group]['usedchans'] + Number(spandata[span]['spandata']['min_ch'])) > spandata[span]['spandata']['reserved_ch']) ? 1 : 0;
                var usedchans = (spandata[span]['spandata']['max_ch'] + add) - startchan

                $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'digitaladd', span: span, groupc: group+1, usedchans: usedchans, startchan: startchan}, function(z){
                    $('#editspan_'+span+'_group_settings_' + (group)).after(z.html);
                    group++;
                    spandata[span]['groups'][group] = {};
                    spandata[span]['groups'][group]['endchan'] = z.endchan;
                    spandata[span]['groups'][group]['usedchans'] = Number(usedchans);
                    spandata[span]['groups'][group]['fxx'] = z.fxx
                    spandata[span]['groups'][group]['startchan'] = Number(z.startchan)
                    $('#editspan_'+span+'_definedchans_' + group).on('change', function() {
                        var usedchans = $(this).val();
                	    update_digital_groups(span,group,usedchans);
                    });
                })
            } else {
                var count = spandata[span]['groups'].length;
                var i = 1;
                var prevkey = 0;
                $.each(spandata[span]['groups'], function(key, value) {
                    if(group < key) {
                        var startchan = spandata[span]['groups'][(prevkey)]['endchan'] + 1
                        var usedchans = $('#editspan_'+span+'_definedchans_'+key).val()                        
                        var selected = 0;
                        if(i == count) {
                            usedchans = spandata[span]['spandata']['max_ch'] - spandata[span]['groups'][prevkey]['endchan']
                            selected = usedchans
                        } else {
                            selected = $('#editspan_'+span+'_definedchans_' + key).val()
                        }
                        $.ajax({
                          url: "config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",
                          dataType: 'json',
                          data: {type: 'digitaladd', span: span, usedchans: usedchans, startchan: startchan},
                          async: false
                        }).done(function(x){
                            $('#editspan_'+span+'_from_'+ key).html(x.fxx)
                            $('#editspan_'+span+'_definedchans_' + key).html(x.select)
                            $('#editspan_'+span+'_definedchans_' + key).val(selected)
                            spandata[span]['groups'][key]['endchan'] = x.endchan
                            spandata[span]['groups'][key]['fxx'] = x.fxx
                            spandata[span]['groups'][key]['startchan'] = x.startchan
                        });
                          
                    }
                    i++;
                    prevkey = key;
                });
            }
        } else {
            //Delete all groups forward
            if((spandata[span]['groups'][group]['startchan'] + spandata[span]['groups'][group]['usedchans']) > spandata[span]['spandata']['max_ch']) {
                var selected = spandata[span]['spandata']['max_ch'] - spandata[span]['groups'][group]['startchan']
                $('#editspan_'+span+'_definedchans_' + group).val(selected)
            }
            $.each(spandata[span]['groups'], function(key, value) {
                if(document.getElementById('editspan_'+span+'_group_settings_' + key) && (key > group)) {
                    $('#editspan_'+span+'_group_settings_' + key).remove();
                    delete spandata[span]['groups'][key]
                }
            })
        }
    })
}
/* End Span Group Automation */


var mp_additional_key = 1;
function mp_add_field() {
    var i = mp_additional_key;
    $('#modprobe_additional_'+ (i-1)).after('<tr id="modprobe_additional_'+i+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><input type="checkbox" id="mp_setting_checkbox_'+i+'" name="mp_setting_checkbox_'+i+'" /><input id="mp_setting_key_'+i+'" name="mp_setting_key_'+i+'" value="" /> =<input id="mp_setting_value_'+i+'" name="mp_setting_value_'+i+'" value="" /> <br /></td></tr>');
    mp_additional_key++;
}

$(function(){    
    //On Focus of module name element then we save the local storage
    $('#module_name').focus(function () { 
        //Local Storage is an object {}
        var settings = {};
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
                }
            })
        } else {
            //Loop over our 'object' (really an array for you php nerds)
            $('.neon').hide();
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
                  if (element.is(":text")) {}
                      
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
        width: 500,
        modal: true,
        buttons: {
            "Save": function() {
                //Local Storage is an object {}
                var settings = {};
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
                })
                //Store data in our storage array
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

var dh_global_additional_key = 1;
function dh_global_add_field() {
    var i = dh_global_additional_key;
    $('#dh_global_additional_'+ (i-1)).after('<tr id="dh_global_additional_'+i+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><input type="checkbox" id="dh_global_setting_checkbox_'+i+'" name="dh_global_setting_checkbox_'+i+'" /><input id="dh_global_setting_key_'+i+'" name="dh_global_setting_key_'+i+'" value="" /> =<input id="dh_global_setting_value_'+i+'" name="dh_global_setting_value_'+i+'" value="" /> <br /></td></tr>');
    dh_global_additional_key++;
}
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