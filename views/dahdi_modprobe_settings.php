<form id="form-modprobe" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=modprobesubmit">
    <div id="modprobe">
        <h2>Modprobe Settings</h2>
        <hr />
        <table width="100%" style="text-align:left;">
            <tr>
                <td style="width:10px;">
                    <label><a href="#" class="info">Module Name:<span>Specify the kernel module used by the installed analog hardware.</span></a></label>
                </td>
                <td>
                    <select id="module_name" name="module_name">
            	    <?php foreach($dahdi_cards->get_drivers_list() as $list) { ?>
            	    <option value="<?php echo $list; ?>" <?php echo set_default($dahdi_cards->get_modprobe('module_name'),$list); ?>><?php echo $list; ?></option>
            	    <?php } ?>
            	    </select>
            	</td>
            </tr>
        </table>
        <table width="100%" id="wctc4xxp_settings" style="text-align:left;display: none;">
            <tr>
                <td style="width:10px;">
                    <label><a href="#" class="info">Mode:<span>In Any mode it'll run 92 channels of either codec.<br/>In G.729 mode it'll run 120 channels<br/>In G.723.1 mode it'll run 92 channels.</span></a></label>
                </td>
                <td>
                  	<input type="checkbox" id="mode_checkbox" name="mode_checkbox" <?php echo ($dahdi_cards->get_modprobe('mode_checkbox'))?'checked':''?> />
                	<select id="mode" name="mode">
                		<option value="any" <?php echo set_default($dahdi_cards->get_modprobe('mode'),'any'); ?>>Any</option>
                		<option value="g723.1" <?php echo set_default($dahdi_cards->get_modprobe('mode'),'g723.1'); ?>>G723.1</option>
                		<option value="g729" <?php echo set_default($dahdi_cards->get_modprobe('mode'),'g729'); ?>>G729</option>
                	</select>
                </td>  
            </tr>
        </table>
        <table width="100%" id="normal_mp_settings" style="text-align:left;">
            <tr>
                <td style="width:10px;">
                    <label for="opermode_checkbox" class="info"><a href="#" class="info">Opermode:<span>Specify the On Hook Speed, Ringer Impedance, Ringer Threshold, Current limiting, Tip/Ring voltage adjustment, Minimum Operational Loop current, and AC Impedance selection as predefined for each countries' analog line characteristics. Select the country in which your Asterisk server is operating. FCC is equivalent to United States. TBR21 is equivalent to Austria, Belgium, Denmark, Finland, France, Germany, Greece, Iceland, Ireland, Italy, Luxembourg, Netherlands, Norway, Portugal, Spain, Sweden, Switzerland, and the United Kingdom. If no choice is specified, the default is FCC.</span></a></label>
                </td>
                <td>
                	<input type="checkbox" id="opermode_checkbox" name="opermode_checkbox" <?php echo ($dahdi_cards->get_modprobe('opermode_checkbox'))?'checked':''?> />
                	<select id="opermode" name="opermode">
                		<option value="USA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'USA'); ?>>USA</option>
                		<option value="ARGENTINA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ARGENTINA'); ?>>ARGENTINA</option>
                		<option value="AUSTRALIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'AUSTRALIA'); ?>>AUSTRALIA</option>
                		<option value="AUSTRIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'AUSTRIA'); ?>>AUSTRIA</option>
                		<option value="BAHRAIN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'BAHRAIN'); ?>>BAHRAIN</option>
                		<option value="BELGIUM" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'BELGIUM'); ?>>BELGIUM</option>
                		<option value="BRAZIL" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'BRAZIL'); ?>>BRAZIL</option>
                		<option value="BULGARIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'BULGARIA'); ?>>BULGARIA</option>
                		<option value="CANADA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CANADA'); ?>>CANADA</option>
                		<option value="CHILE" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CHILE'); ?>>CHILE</option>
                		<option value="CHINA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CHINA'); ?>>CHINA</option>
                		<option value="COLUMBIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'COLUMBIA'); ?>>COLUMBIA</option>
                		<option value="CROATIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CROATIA'); ?>>CROATIA</option>
                		<option value="CYRPUS" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CYRPUS'); ?>>CYRPUS</option>
                		<option value="CZECH" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'CZECH'); ?>>CZECH</option>
                		<option value="DENMARK" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'DENMARK'); ?>>DENMARK</option>
                		<option value="ECUADOR" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ECUADOR'); ?>>ECUADOR</option>
                		<option value="EGYPT" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'EGYPT'); ?>>EGYPT</option>
                		<option value="ELSALVADOR" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ELSALVADOR'); ?>>ELSALVADOR</option>
                		<option value="FCC" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'FCC'); ?>>FCC</option>
                		<option value="FINLAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'FINLAND'); ?>>FINLAND</option>
                		<option value="FRANCE" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'FRANCE'); ?>>FRANCE</option>
                		<option value="GERMANY" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'GERMANY'); ?>>GERMANY</option>
                		<option value="GREECE" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'GREECE'); ?>>GREECE</option>
                		<option value="GUAM" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'GUAM'); ?>>GUAM</option>
                		<option value="HONGKONG" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'HONGKONG'); ?>>HONGKONG</option>
                		<option value="HUNGARY" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'HUNGARY'); ?>>HUNGARY</option>
                		<option value="ICELAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ICELAND'); ?>>ICELAND</option>
                		<option value="INDIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'INDIA'); ?>>INDIA</option>
                		<option value="INDONESIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'INDONESIA'); ?>>INDONESIA</option>
                		<option value="IRELAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'IRELAND'); ?>>IRELAND</option>
                		<option value="ISRAEL" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ISRAEL'); ?>>ISRAEL</option>
                		<option value="ITALY" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ITALY'); ?>>ITALY</option>
                		<option value="JAPAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'JAPAN'); ?>>JAPAN</option>
                		<option value="JORDAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'JORDAN'); ?>>JORDAN</option>
                		<option value="KAZAKHSTAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'KAZAKHSTAN'); ?>>KAZAKHSTAN</option>
                		<option value="KUWAIT" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'KUWAIT'); ?>>KUWAIT</option>
                		<option value="LATVIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'LATVIA'); ?>>LATVIA</option>
                		<option value="LEBANON" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'LEBANON'); ?>>LEBANON</option>
                		<option value="LUXEMBOURG" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'LUXEMBOURG'); ?>>LUXEMBOURG</option>
                		<option value="MACAO" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'MACACO'); ?>>MACAO</option>
                		<option value="MALAYSIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'MALAYSIA'); ?>>MALAYSIA</option>
                		<option value="MALTA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'MALTA'); ?>>MALTA</option>
                		<option value="MEXICO" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'MEXICO'); ?>>MEXICO</option>
                		<option value="MOROCCO" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'MOROCCO'); ?>>MOROCCO</option>
                		<option value="NETHERLANDS" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'NETHERLANDS'); ?>>NETHERLANDS</option>
                		<option value="NEWZEALAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'NEWZEALAND'); ?>>NEWZEALAND</option>
                		<option value="NIGERIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'NIGERIA'); ?>>NIGERIA</option>
                		<option value="NORWAY" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'NORWAY'); ?>>NORWAY</option>
                		<option value="OMAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'OMAN'); ?>>OMAN</option>
                		<option value="PAKISTAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'PAKISTAN'); ?>>PAKISTAN</option>
                		<option value="PERU" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'PERU'); ?>>PERU</option>
                		<option value="PHILIPPINES" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'PHILIPPINES'); ?>>PHILIPPINES</option>
                		<option value="POLAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'POLAND'); ?>>POLAND</option>
                		<option value="PORTUGAL" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'PORTUGAL'); ?>>PORTUGAL</option>
                		<option value="ROMANIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'ROMANIA'); ?>>ROMANIA</option>
                		<option value="RUSSIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'RUSSIA'); ?>>RUSSIA</option>
                		<option value="SAUDIARABIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SAUDIARABIA'); ?>>SAUDIARABIA</option>
                		<option value="SINGAPORE" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SINGAPORE'); ?>>SINGAPORE</option>
                		<option value="SLOVAKIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SLOVAKIA'); ?>>SLOVAKIA</option>
                		<option value="SLOVENIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SLOVENIA'); ?>>SLOVENIA</option>
                		<option value="SOUTHAFRICA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SOUTHAFRICA'); ?>>SOUTHAFRICA</option>
                		<option value="SOUTHKOREA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SOUTHKOREA'); ?>>SOUTHKOREA</option>
                		<option value="SPAIN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SPAIN'); ?>>SPAIN</option>
                		<option value="SWEDEN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SWEDEN'); ?>>SWEDEN</option>
                		<option value="SWITZERLAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SWITZERLAND'); ?>>SWITZERLAND</option>
                		<option value="SYRIA" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'SYRIA'); ?>>SYRIA</option>
                		<option value="TAIWAN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'TAIWAN'); ?>>TAIWAN</option>
                		<option value="TBR21" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'TBR21'); ?>>TBR21</option>
                		<option value="THAILAND" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'THAILAND'); ?>>THAILAND</option>
                		<option value="UAE" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'UAE'); ?>>UAE</option>
                		<option value="UK" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'UK'); ?>>UK</option>
                		<option value="YEMEN" <?php echo set_default($dahdi_cards->get_modprobe('opermode'),'YEMEN'); ?>>YEMEN</option>
                	</select>
                </td>
            </tr> 
            <tr>
                <td style="width:10px;"> 
                    <label for="alawoverride_checkbox"><a href="#" class="info">A-law Override:<span>Specify the audio compression scheme (codec) to be used for analog lines. North American users should choose ulaw. All other countries, unless otherwise known, should be assumed to be alaw. If no choice is specified, the default is ulaw. Confirm the scheme which will be best for operation.</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="alawoverride_checkbox" name="alawoverride_checkbox" <?php echo ($dahdi_cards->get_modprobe('alawoverride_checkbox'))?'checked':''?> />
                    <select id="alawoverride" name="alawoverride">
                		<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('alawoverride'),'0'); ?>>ulaw</option>
                		<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('alawoverride'),'1'); ?>>alaw</option>
                	</select>
                </td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="fxs_honor_mode_checkbox"><a href="#" class="info">FXS Honor Mode:<span>Specify whether to apply the opermode setting to your FXO modules only, or to both FXS and FXO modules. If no choice is specified, the default is apply opermode to fxo modules only.</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="fxs_honor_mode_checkbox" name="fxs_honor_mode_checkbox" <?php echo ($dahdi_cards->get_modprobe('fxs_honor_mode_checkbox'))?'checked':''?> />
                	<select id="fxs_honor_mode" name="fxs_honor_mode">
                		<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('fxs_honor_mode'),'0'); ?>>Apply Opermode to FXO Modules</option>
                		<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('fxs_honor_mode'),'1'); ?>>Apply Opermode to FXS and FXO Modules</option>
                	</select>
                </td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="boostringer_checkbox"><a href="#" class="info">Boostringer:<span>Specify the voltage used for ringing an analog phone. Normal will set the ring voltage to 40V, and Peak will set the voltage to 89V. If no choice is specified, the default is normal.</span></a></label>
                </td>
                <td>
                   	<input type="checkbox" id="boostringer_checkbox" name="boostringer_checkbox" <?php echo ($dahdi_cards->get_modprobe('boostringer_checkbox'))?'checked':''?> />
                	<select id="boostringer" name="boostringer">
                		<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('boostringer'),'0'); ?>>Normal</option>
                		<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('boostringer'),'1'); ?>>Peak (89v)</option>
                	</select>
                </td>
            </tr> 
            <tr>
                <td style="width:10px;">
                    <label for="fastringer_checkbox"><a href="#" class="info">Fastringer:<span>Specify whether to apply Fast Ringer operation. Setting Fast Ringer (25Hz) (commonly used in conjunction with the Low Power option) increases the ringing speed to 25Hz. If no choice is specified, the default is normal.</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="fastringer_checkbox" name="fastringer_checkbox" <?php echo ($dahdi_cards->get_modprobe('fastringer_checkbox'))?'checked':''?> />
                	<select id="fastringer" name="fastringer">
                		<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('fastringer'),'0'); ?>>Normal</option>
                		<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('fastringer'),'1'); ?>>Fast Ringer (25hz)</option>
                	</select>
                </td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="lowpower_checkbox"><a href="#" class="info">Lowpower:<span>Specify whether to apply Low Power operation. Setting Fast Ringer to 50V peak in conjunction with the Fast Ringer option increases the peak voltage during Fast Ringer operation to 50V. If no choice is specified, the default is normal.</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="lowpower_checkbox" name="lowpower_checkbox" <?php echo ($dahdi_cards->get_modprobe('lowpower_checkbox'))?'checked':''?> />
            	    <select id="lowpower" name="lowpower">
            		    <option value="0" <?php echo set_default($dahdi_cards->get_modprobe('lowpower'),'0'); ?>>Normal</option>
            		    <option value="1" <?php echo set_default($dahdi_cards->get_modprobe('lowpower'),'1'); ?>>Fast Ringer to 50v Peak</option>
            	    </select>
            	</td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="ringdetect_checkbox"><a href="#" class="info">Ring Detect:<span>Specify whether to apply normal ring detection, or a full wave detection to prevent false ring detection for lines where CallerID is sent before the first ring and proceeded by a polarity reversal (as in the United Kingdom). If you are experiencing trouble with detecting CallerID from analog service providers, or have lines which exhibit a polarity reversal before CallerID is transmitted from the provider, then select Full Wave. If no choice is specified, the default is standard.</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="ringdetect_checkbox" name="ringdetect_checkbox" <?php echo ($dahdi_cards->get_modprobe('ringdetect_checkbox'))?'checked':''?> />
                	<select id="ringdetect" name="ringdetect">
                		<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('ringdetect'),'0'); ?>>Standard</option>
                		<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('ringdetect'),'1'); ?>>Full Wave</option>
                	</select>
                </td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="mwi_checkbox"><a href="#" class="info">MWI Mode:<span>Specify the type of Message Waiting Indicator detection to be done on FXO ports. If no choice is specified, the default is none. The following options are available:
                	<ul>
                		<li>none - Performs no detection </li>
                		<li>FSK - Performs Frequency Shift Key detection</li>
                		<li>NEON - Performs Neon MWI detection.</li>
                	</ul>
                	</span></a></label>
                </td>
                <td>
                    <input type="checkbox" id="mwi_checkbox" name="mwi_checkbox" <?php echo ($dahdi_cards->get_modprobe('mwi_checkbox'))?'checked':''?> />
                	<select id="mwi" name="mwi">
                		<option value="none" <?php echo set_default($dahdi_cards->get_modprobe('mwi'),'none'); ?>>None</option>
                		<option value="fsk" <?php echo set_default($dahdi_cards->get_modprobe('mwi'),'fsk'); ?>>FSK</option>
                		<option value="neon" <?php echo set_default($dahdi_cards->get_modprobe('mwi'),'neon'); ?>>NEON</option>
                	</select>
                </td>
            </tr>
            <tr class="neon" <?php echo (($dahdi_cards->get_modprobe('mwi') != 'neon') ? ' style="display:none;"' : "")?>>
                <td style="width:10px;">
                </td>
                <td>
                    <label for="neon_voltage">Neon MWI Voltage Level: </label>
                	<input id="neon_voltage" name="neon_voltage" size="2" value="<?php echo $dahdi_cards->get_modprobe('neon_voltage')?>" /><br />
                	<label for="neon_offlimit">Neon MWI Off Limit: </label>
                	<input id="neon_offlimit" name="neon_offlimit" size="4" value="<?php echo $dahdi_cards->get_modprobe('neon_offlimit')?>" />
                </td>
            </tr>
        </table>
        <table width="100%" id="vpmsettings" <?php echo ((!$dahdi_cards->has_vpm())? ' style="display:none;"': "")?>>
            <tr>
                <td style="width:10px;">
                    <label for="echocan_nlp_type"><a href="#" class="info">Echo Canc. NLP Type:<span> This option allows you to specify the type of Non Linear Processor you want applied to the post echo-cancelled audio reflections received from analog connections (VPMADT032 only). There are several options:
            		<ul>
            			<li>None - This setting disables NLP processing and is not a recommended setting. Under most circumstances, choos- ing None will cause some residual echo.</li>
            			<li>Mute - This setting causes the NLP to mute inbound audio streams while a user connected to Asterisk is speaking. For users in quiet environments, Mute may be acceptable.</li>
            			<li>Random Noise - This setting causes the NLP to inject random noise to mask the echo reflection. For users in normal environments, Random Noise may be acceptable.</li>
            			<li>Hoth Noise - This setting causes the NLP to inject a low-end Gaussian noise with a frequency spectrum similar to voice. For users in normal environments, Hoth Noise may be acceptable.</li>
            			<li>Suppression NLP - This setting causes the NLP to suppress echo reflections by reducing the amplitude of their volume. Suppression may be used in combination with the Echo cancellation NLP Max Suppression option. For users in loud environments, Suppression NLP may be the best option. This is the default setting for the Echo Cancellation NLP Type option.</li>
            		</ul>
            		</span></a></label>
            	</td>
            	<td>
            	    <select id="echocan_nlp_type" name="echocan_nlp_type">
            			<option value="0" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_type'),'0'); ?>>None</option>
            			<option value="1" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_type'),'1'); ?>>Mute</option>
            			<option value="2" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_type'),'2'); ?>>Random Noise</option>
            			<option value="3" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_type'),'3'); ?>>Hoth Noise</option>
            			<option value="4" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_type'),'4'); ?>>Suppression NLP (default)</option>
            		</select>
            	</td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="echocan_nlp_threshold"><a href="#" class="info">Echo Canc. NLP Threshold:<span>This option allows you to specify the threshold, in dB difference between the received audio (post echo cancellation) and the transmitted audio, for when the NLP will engage (VPMADT032 only). The default setting is 24 dB.</span></a></label>
                </td>
                <td>
                    <select id="echocan_nlp_threshold" name="echocan_nlp_threshold">
            		    <?php for($i=0; $i<=50; $i++) { ?>
            		    <option value="<?php echo $i; ?>" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_threshold'),$i); ?>><?php echo $i; ?></option>
            		    <?php } ?>
            		</select>
                </td>
            </tr>
            <tr>
                <td style="width:10px;">
                    <label for="echocan_nlp_max_supp"><a href="#" class="info">Echo Canc. NLP Max Suppression:<span>This option, only functional when the Echo Cancellation NLP Type option is set to Suppression NLP, specifies the maximum amount of dB that the NLP should attenuate the residual echo (VPMADT032 only). Lower numbers mean that the NLP will provide less suppression (the residual echo will sound louder). Higher numbers, especially those approaching or equaling the Echo Cancellation NLP Threshold option, will nearly mute the residual echo. The default setting is 24 dB.</span></a></label>
                </td>
                <td>
                    <select id="echocan_nlp_max_supp" name="echocan_nlp_max_supp">
            		    <?php for($i=0; $i<=50; $i++) { ?>
            		    <option value="<?php echo $i; ?>" <?php echo set_default($dahdi_cards->get_modprobe('echocan_nlp_max_supp'),$i); ?>><?php echo $i; ?></option>
            		    <?php } ?>
            		</select>
                </td>
        </table>
        <?php
        $mp = $dahdi_cards->get_all_modprobe($dahdi_cards->get_modprobe('module_name'));
        if(isset($mp['additionals'])) {
            foreach($mp['additionals'] as $key => $value) {
                $mp_key = $key;
                $mp_val = $value;
                unset($mp['additionals'][$key]);
                break;
            }
        }
        ?>
        <table width="100%" style="text-align:left;">
            <tr id="mp_additional_0">
                <td style="width:10px;vertical-align:top;">
                    <label>Other Modprobe Settings: </label>
                </td>
                <td style="vertical-align:bottom;">
                    <a href="#" onclick="mp_delete_field(0,'<?php echo $dahdi_cards->get_modprobe('module_name')?>')"><img height="10px" src="images/trash.png"></a>
                    <input type="hidden" name="mp_setting_add[]" value="0" />
                    <input type="hidden" id="mp_setting_origsetting_key_0" name="mp_setting_origsetting_key_0" value="<?php echo $mp_key?>" />
                    <input id="mp_setting_key_0" name="mp_setting_key_0" value="<?php echo $mp_key?>" /> =
                    <input id="mp_setting_value_0" name="mp_setting_value_0" value="<?php echo $mp_val?>" /> <br />
                </td>
            </tr>
            <?php 
            $a = 1;
            if(isset($mp['additionals'])) {
                foreach($mp['additionals'] as $key => $value) {?>
                    <tr class="mp_js_additionals" id="mp_additional_<?php echo $a?>">
                        <td style="width:10px;vertical-align:top;">
                        </td>
                        <td style="vertical-align:bottom;">
                            <a href="#" onclick="mp_delete_field(<?php echo $a?>,'<?php echo $dahdi_cards->get_modprobe('module_name')?>')"><img height="10px" src="images/trash.png"></a>
                            <input type="hidden" name="mp_setting_add[]" value="<?php echo $a?>" />
                            <input type="hidden" id="mp_setting_origsetting_key_<?php echo $a?>" name="mp_setting_origsetting_key_<?php echo $a?>" value="<?php echo $key?>" />
                            <input id="mp_setting_key_<?php echo $a?>" name="mp_setting_key_<?php echo $a?>" value="<?php echo $key?>" /> =
                            <input id="mp_setting_value_<?php echo $a?>" name="mp_setting_value_<?php echo $a?>" value="<?php echo $value?>" /> <br />
                        </td>
                    </tr>
                <?php 
                    $a++;
                }
            } ?>
            <tr id="mp_add">
                <td> 
                </td>
                <td>
                    <a id="mp_add_button" style="cursor: pointer;" onclick="mp_add_field(<?php echo $a?>,'<?php echo $dahdi_cards->get_modprobe('module_name')?>')"><img src="assets/dahdiconfig/images/add.png"></a>
                </td>
            </tr>
        </table>
    </div>
</form>