/* Popup Box Function */
function dahdi_modal_settings(type,id) {
    if(typeof id !== 'undefined') {
        $( "#"+type+"-settings-"+id ).dialog( "open" );
    } else {
        $( "#"+type+"-settings" ).dialog( "open" );
    }
}
/* End Popup Box Function */

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
                var group_num = $('#editspan_'+span+'_group_'+group).val();
                group_num = $.isNumeric(group_num) ? group_num : group;
                $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'digitaladd', span: span, groupc: group+1, usedchans: usedchans, startchan: startchan, group_num: (Number(group_num)+1)}, function(z){
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
/* Custom settings for Global Settings */
/* Delete Custom Setting */
function dh_global_delete_field(id) {
    var origkey = $("#dh_global_origsetting_key_"+id).val();
    var key = $("#dh_global_setting_key_"+id).val();
    var val = $("#dh_global_setting_val_"+id).val();
    if(typeof origkey === 'undefined') {
        if(id > 0) {
            $('#dh_global_additional_'+ id).remove();
        } else {
            $('#dh_global_setting_key_0').val('');
            $('#dh_global_setting_value_0').val('');
        }
    } else {
        if(id > 0) {
            $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'globalsettingsremove', keyword: key, origkeyword: origkey, value: val}, function(z){
                $('#dh_global_additional_'+ id).remove();
            });
        } else {
            $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'globalsettingsremove', keyword: key, origkeyword: origkey, value: val}, function(z){
                $('#dh_global_setting_key_0').val('');
                $('#dh_global_setting_value_0').val('');
            });
        }
    }
}
/* End Delete Custom Setting */
/* Add Custom Setting */
var max_dh_global = 0;
//var dh_global_additional_key = 0;
function dh_global_add_field(start) {
    var i = (start < max_dh_global) ? max_dh_global : start;
    $("#dh_global_add").before('<tr id="dh_global_additional_'+i+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><a href="#" onclick="dh_global_delete_field('+i+')"><img height="10px" src="images/trash.png"></a> <input type="hidden" name="dh_global_add[]" value="'+i+'" /><input id="dh_global_setting_key_'+i+'" name="dh_global_setting_key_'+i+'" value="" /> = <input id="dh_global_setting_value_'+i+'" name="dh_global_setting_value_'+i+'" value="" /> <br /></td></tr>');
    max_dh_global = i+1;
}
/* End Add Custom Setting */
/* End Custom settings for Global Settings */

var max_mp = 0;
function mp_add_field(start,module) {
    var i = (start < max_mp) ? max_mp : start;
    $("#mp_add").before('<tr class="mp_js_additionals" id="mp_additional_'+i+'"><td style="width:10px;vertical-align:top;"></td><td style="vertical-align:bottom;"><a href="#" onclick="mp_delete_field('+i+',\''+module+'\')"><img height="10px" src="images/trash.png"></a> <input type="hidden" name="mp_setting_add[]" value="'+i+'" /> <input id="mp_setting_key_'+i+'" name="mp_setting_key_'+i+'" value="" /> = <input id="mp_setting_value_'+i+'" name="mp_setting_value_'+i+'" value="" /> <br /></td></tr>');
    max_mp = i+1;
}

function mp_delete_field(id,module) {
    var origkey = $("#mp_setting_origsetting_key_"+id).val();
    var key = $("#mp_setting_key_"+id).val();
    var val = $("#mp_setting_val_"+id).val();
    if(typeof origkey === 'undefined') {
        if(id > 0) {
            $('#mp_additional_'+ id).remove();
        } else {
            $('#mp_setting_key_0').val('');
            $('#mp_setting_value_0').val('');
        }
    } else {
        if(id > 0) {
            $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'mpsettingsremove', mod: module, keyword: key, origkeyword: origkey, value: val}, function(z){
                $('#mp_additional_'+ id).remove();
            });
        } else {
            $.getJSON("config.php?quietmode=1&handler=file&module=dahdiconfig&file=ajax.html.php",{type: 'mpsettingsremove', mod: module, keyword: key, origkeyword: origkey, value: val}, function(z){
                $('#mp_setting_key_0').val('');
                $('#mp_setting_value_0').val('');
            });
        }
    }
}