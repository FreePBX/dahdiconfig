<h2>Advanced Settings</h2>
<hr />
<form name="dahdi_advanced_settings" method="post" action="/admin/config.php?type=setup&display=dahdi">
<input type="hidden" name="display" value="dahdi" />
<input type="hidden" name="action" value="edit" />
<div class="setting">
	<label for="module_name"><a href="#" class="info">Module Name:<span>Specify the kernel module used by the installed analog hardware.</span></a></label>
	<input type="text" id="module_name" name="module_name" size="10" value="<?=$dahdi_cards->get_advanced('module_name')?>" />
</div>
<div class="setting">
	<label for="tone_region"><a href="#" class="info">Tone Region:<span>Please choose your country or your nearest neighboring country for default Tones (Ex: dialtone, busy tone, ring tone etc.)</span></a></label>
	<select id="tone_region" name="tone_region">
		<option value="us">United States/North America</option>
		<option value="au">Australia</option>
		<option value="fr">France</option>
		<option value="nl">Netherlands</option>
		<option value="uk">United Kingdom</option>
		<option value="fi">Finland</option>
		<option value="es">Spain</option>
		<option value="jp">Japan</option>
		<option value="no">Norway</option>
		<option value="at">Austria</option>
		<option value="nz">New Zealand</option>
		<option value="it">Italy</option>
		<option value="us-old">United States Circa 1950 / North America</option>
		<option value="gr">Greece</option>
		<option value="tw">Taiwan</option>
		<option value="cl">Chile</option>
		<option value="se">Sweden</option>
		<option value="be">Belgium</option>
		<option value="sg">Singapore</option>
		<option value="il">Israel</option>
		<option value="br">Brazil</option>
		<option value="hu">Hungary</option>
		<option value="lt">Lithuania</option>
		<option value="pl">Poland</option>
		<option value="za">South Africa</option>
		<option value="pt">Portugal</option>
		<option value="ee">Estonia</option>
		<option value="mx">Mexico</option>
		<option value="in">India</option>
		<option value="de">Germany</option>
		<option value="ch">Switzerland</option>
		<option value="dk">Denmark</option>
		<option value="cz">Czech Republic</option>
		<option value="cn">China</option>
		<option value="ar">Argentina</option>
		<option value="my">Malaysia</option>
		<option value="th">Thailand</option>
		<option value="bg">Bulgaria</option>
		<option value="ve">Venezuela</option>
		<option value="ph">Philippines</option>
		<option value="ru">Russian Federation</option>
	</select>
</div>
<div class="setting">
	<label for="opermode_checkbox" class="info"><a href="#" class="info">Opermode:<span>Specify the On Hook Speed, Ringer Impedance, Ringer Threshold, Current limiting, Tip/Ring voltage adjustment, Minimum Operational Loop current, and AC Impedance selection as predefined for each countries' analog line characteristics. Select the country in which your Asterisk server is operating. FCC is equivalent to United States. TBR21 is equivalent to Austria, Belgium, Denmark, Finland, France, Germany, Greece, Iceland, Ireland, Italy, Luxembourg, Netherlands, Norway, Portugal, Spain, Sweden, Switzerland, and the United Kingdom. If no choice is specified, the default is FCC.</span></a></label>
	<input type="checkbox" id="opermode_checkbox" name="opermode_checkbox" <?=($dahdi_cards->get_advanced('opermode_checkbox'))?'checked':''?> />
	<select id="opermode" name="opermode">
		<option value="USA">USA</option>
		<option value="ARGENTINA">ARGENTINA</option>
		<option value="AUSTRALIA">AUSTRALIA</option>
		<option value="AUSTRIA">AUSTRIA</option>
		<option value="BAHRAIN">BAHRAIN</option>
		<option value="BELGIUM">BELGIUM</option>
		<option value="BRAZIL">BRAZIL</option>
		<option value="BULGARIA">BULGARIA</option>
		<option value="CANADA">CANADA</option>
		<option value="CHILE">CHILE</option>
		<option value="CHINA">CHINA</option>
		<option value="COLUMBIA">COLUMBIA</option>
		<option value="CROATIA">CROATIA</option>
		<option value="CYRPUS">CYRPUS</option>
		<option value="CZECH">CZECH</option>
		<option value="DENMARK">DENMARK</option>
		<option value="ECUADOR">ECUADOR</option>
		<option value="EGYPT">EGYPT</option>
		<option value="ELSALVADOR">ELSALVADOR</option>
		<option value="FCC">FCC</option>
		<option value="FINLAND">FINLAND</option>
		<option value="FRANCE">FRANCE</option>
		<option value="GERMANY">GERMANY</option>
		<option value="GREECE">GREECE</option>
		<option value="GUAM">GUAM</option>
		<option value="HONGKONG">HONGKONG</option>
		<option value="HUNGARY">HUNGARY</option>
		<option value="ICELAND">ICELAND</option>
		<option value="INDIA">INDIA</option>
		<option value="INDONESIA">INDONESIA</option>
		<option value="IRELAND">IRELAND</option>
		<option value="ISRAEL">ISRAEL</option>
		<option value="ITALY">ITALY</option>
		<option value="JAPAN">JAPAN</option>
		<option value="JORDAN">JORDAN</option>
		<option value="KAZAKHSTAN">KAZAKHSTAN</option>
		<option value="KUWAIT">KUWAIT</option>
		<option value="LATVIA">LATVIA</option>
		<option value="LEBANON">LEBANON</option>
		<option value="LUXEMBOURG">LUXEMBOURG</option>
		<option value="MACAO">MACAO</option>
		<option value="MALAYSIA">MALAYSIA</option>
		<option value="MALTA">MALTA</option>
		<option value="MEXICO">MEXICO</option>
		<option value="MOROCCO">MOROCCO</option>
		<option value="NETHERLANDS">NETHERLANDS</option>
		<option value="NEWZEALAND">NEWZEALAND</option>
		<option value="NIGERIA">NIGERIA</option>
		<option value="NORWAY">NORWAY</option>
		<option value="OMAN">OMAN</option>
		<option value="PAKISTAN">PAKISTAN</option>
		<option value="PERU">PERU</option>
		<option value="PHILIPPINES">PHILIPPINES</option>
		<option value="POLAND">POLAND</option>
		<option value="PORTUGAL">PORTUGAL</option>
		<option value="ROMANIA">ROMANIA</option>
		<option value="RUSSIA">RUSSIA</option>
		<option value="SAUDIARABIA">SAUDIARABIA</option>
		<option value="SINGAPORE">SINGAPORE</option>
		<option value="SLOVAKIA">SLOVAKIA</option>
		<option value="SLOVENIA">SLOVENIA</option>
		<option value="SOUTHAFRICA">SOUTHAFRICA</option>
		<option value="SOUTHKOREA">SOUTHKOREA</option>
		<option value="SPAIN">SPAIN</option>
		<option value="SWEDEN">SWEDEN</option>
		<option value="SWITZERLAND">SWITZERLAND</option>
		<option value="SYRIA">SYRIA</option>
		<option value="TAIWAN">TAIWAN</option>
		<option value="TBR21">TBR21</option>
		<option value="THAILAND">THAILAND</option>
		<option value="UAE">UAE</option>
		<option value="UK">UK</option>
		<option value="YEMEN">YEMEN</option>
	</select>
</div>
<div class="setting">
	<label for="alawoverride_checkbox"><a href="#" class="info">A-law Override:<span>Specify the audio compression scheme (codec) to be used for analog lines. North American users should choose ulaw. All other countries, unless otherwise known, should be assumed to be alaw. If no choice is specified, the default is ulaw. Confirm the scheme which will be best for operation.</span></a></label>
	<input type="checkbox" id="alawoverride_checkbox" name="alawoverride_checkbox" <?=($dahdi_cards->get_advanced('alawoverride_checkbox'))?'checked':''?> />
	<select id="alawoverride" name="alawoverride">
		<option value="0">ulaw</option>
		<option value="1">alaw</option>
	</select>
</div>
<div class="setting">
	<label for="fxs_honor_mode_checkbox"><a href="#" class="info">FXS Honor Mode:<span>Specify whether to apply the opermode setting to your FXO modules only, or to both FXS and FXO modules. If no choice is specified, the default is apply opermode to fxo modules only.</span></a></label>
	<input type="checkbox" id="fxs_honor_mode_checkbox" name="fxs_honor_mode_checkbox" <?=($dahdi_cards->get_advanced('fxs_honor_mode_checkbox'))?'checked':''?> />
	<select id="fxs_honor_mode" name="fxs_honor_mode">
		<option value="0">Apply Opermode to FXO Modules</option>
		<option value="1">Apply Opermode to FXS and FXO Modules</option>
	</select>
</div>
<div class="setting">
	<label for="boostringer_checkbox"><a href="#" class="info">Boostringer:<span>Specify the voltage used for ringing an analog phone. Normal will set the ring voltage to 40V, and Peak will set the voltage to 89V. If no choice is specified, the default is normal.</span></a></label>
	<input type="checkbox" id="boostringer_checkbox" name="boostringer_checkbox" <?=($dahdi_cards->get_advanced('boostringer_checkbox'))?'checked':''?> />
	<select id="boostringer" name="boostringer">
		<option value="0">Normal</option>
		<option value="1">Peak (89v)</option>
	</select>
</div>
<div class="setting">
	<label for="fastringer_checkbox"><a href="#" class="info">Fastringer:<span>Specify whether to apply Fast Ringer operation. Setting Fast Ringer (25Hz) (commonly used in conjunction with the Low Power option) increases the ringing speed to 25Hz. If no choice is specified, the default is normal.</span></a></label>
	<input type="checkbox" id="fastringer_checkbox" name="fastringer_checkbox" <?=($dahdi_cards->get_advanced('fastringer_checkbox'))?'checked':''?> />
	<select id="fastringer" name="fastringer">
		<option value="0">Normal</option>
		<option value="1">Fast Ringer (25hz)</option>
	</select>
</div>
<div class="setting">
	<label for="lowpower_checkbox"><a href="#" class="info">Lowpower:<span>Specify whether to apply Low Power operation. Setting Fast Ringer to 50V peak in conjunction with the Fast Ringer option increases the peak voltage during Fast Ringer operation to 50V. If no choice is specified, the default is normal.</span></a></label>
	<input type="checkbox" id="lowpower_checkbox" name="lowpower_checkbox" <?=($dahdi_cards->get_advanced('lowpower_checkbox'))?'checked':''?> />
	<select id="lowpower" name="lowpower">
		<option value="0">Normal</option>
		<option value="1">Fast Ringer to 50v Peak</option>
	</select>
</div>
<div class="setting">
	<label for="ringdetect_checkbox"><a href="#" class="info">Ring Detect:<span>Specify whether to apply normal ring detection, or a full wave detection to prevent false ring detection for lines where CallerID is sent before the first ring and proceeded by a polarity reversal (as in the United Kingdom). If you are experiencing trouble with detecting CallerID from analog service providers, or have lines which exhibit a polarity reversal before CallerID is transmitted from the provider, then select Full Wave. If no choice is specified, the default is standard.</span></a></label>
	<input type="checkbox" id="ringdetect_checkbox" name="ringdetect_checkbox" <?=($dahdi_cards->get_advanced('ringdetect_checkbox'))?'checked':''?> />
	<select id="ringdetect" name="ringdetect">
		<option value="0">Standard</option>
		<option value="1">Full Wave</option>
	</select>
</div>
<div class="setting">
	<label for="mwi_checkbox"><a href="#" class="info">MWI Mode:<span>Specify the type of Message Waiting Indicator detection to be done on FXO ports. If no choice is specified, the default is none. The following options are available:
	<ul>
		<li>none - Performs no detection </li>
		<li>FSK - Performs Frequency Shift Key detection</li>
		<li>NEON - Performs Neon MWI detection.</li>
	</ul>
	</span></a></label>
	<input type="checkbox" id="mwi_checkbox" name="mwi_checkbox" <?=($dahdi_cards->get_advanced('mwi_checkbox'))?'checked':''?> />
	<select id="mwi" name="mwi">
		<option value="none">None</option>
		<option value="fsk">FSK</option>
		<option value="neon">NEON</option>
	</select>
</div>
<div class="setting neon"<?=(($dahdi_cards->get_advanced['mwi'] != 'neon') ? ' style="display:none;"' : "")?>>
	<label for="neon_voltage">Neon MWI Voltage Level: </label>
	<input id="neon_voltage" name="neon_voltage" size="2" value="<?=$dahdi_cards->get_advanced('neon_voltage')?>" /><br />
	<label for="neon_offlimit">Neon MWI Off Limit: </label>
	<input id="neon_offlimit" name="neon_offlimit" size="4" value="<?=$dahdi_cards->get_advanced('neon_offlimit')?>" />
</div>
<div id="vpmsettings"<?=((!$dahdi_cards->has_vpm())? ' style="display:none;"': "")?>>
	<div class="setting">
		<label for="echocan_nlp_type"><a href="#" class="info">Echo Canc. NLP Type:<span> This option allows you to specify the type of Non Linear Processor you want applied to the post echo-cancelled audio reflections received from analog connections (VPMADT032 only). There are several options:
		<ul>
			<li>None - This setting disables NLP processing and is not a recommended setting. Under most circumstances, choos- ing None will cause some residual echo.</li>
			<li>Mute - This setting causes the NLP to mute inbound audio streams while a user connected to Asterisk is speaking. For users in quiet environments, Mute may be acceptable.</li>
			<li>Random Noise - This setting causes the NLP to inject random noise to mask the echo reflection. For users in normal environments, Random Noise may be acceptable.</li>
			<li>Hoth Noise - This setting causes the NLP to inject a low-end Gaussian noise with a frequency spectrum similar to voice. For users in normal environments, Hoth Noise may be acceptable.</li>
			<li>Suppression NLP - This setting causes the NLP to suppress echo reflections by reducing the amplitude of their volume. Suppression may be used in combination with the Echo cancellation NLP Max Suppression option. For users in loud environments, Suppression NLP may be the best option. This is the default setting for the Echo Cancellation NLP Type option.</li>
		</ul>
		</span></a></label>
		<select id="echocan_nlp_type" name="echocan_nlp_type">
			<option value="0">None</option>
			<option value="1">Mute</option>
			<option value="2">Random Noise</option>
			<option value="3">Hoth Noise</option>
			<option value="4">Suppression NLP (default)</option>
		</select>
	</div>
	<div class="setting">
		<label for="echocan_nlp_threshold"><a href="#" class="info">Echo Canc. NLP Threshold:<span>This option allows you to specify the threshold, in dB difference between the received audio (post echo cancellation) and the transmitted audio, for when the NLP will engage (VPMADT032 only). The default setting is 24 dB.</span></a></label>
		<select id="echocan_nlp_threshold" name="echocan_nlp_threshold"></select>
	</div>
	<div class="setting">
		<label for="echocan_nlp_max_supp"><a href="#" class="info">Echo Canc. NLP Max Suppression:<span>This option, only functional when the Echo Cancellation NLP Type option is set to Suppression NLP, specifies the maximum amount of dB that the NLP should attenuate the residual echo (VPMADT032 only). Lower numbers mean that the NLP will provide less suppression (the residual echo will sound louder). Higher numbers, especially those approaching or equaling the Echo Cancellation NLP Threshold option, will nearly mute the residual echo. The default setting is 24 dB.</span></a></label>
		<select id="echocan_nlp_max_supp" name="echocan_nlp_max_supp"></select>
	</div>
</div>
<div class="btn_container">
	<input type="submit" id="advanced_cancel" name="advanced_cancel" value="Cancel" />
	<input type="submit" id="advanced_submit" name="advanced_submit" value="Save" />
</div>
</form>

<script>

	for(var i=0; i<=50; i++) {
		$('#echocan_nlp_max_supp').append('<option value="'+i+'">'+i+'</option>');
		$('#echocan_nlp_threshold').append('<option value="'+i+'">'+i+'</option>');
	}

	ChangeSelectByValue('tone_region', '<?=$dahdi_cards->get_advanced('tone_region')?>', true);
	ChangeSelectByValue('opermode', '<?=$dahdi_cards->get_advanced('opermode')?>', true);
	ChangeSelectByValue('alawoverride', '<?=$dahdi_cards->get_advanced('alawoverride')?>', true);
	ChangeSelectByValue('fxs_honor_mode', '<?=$dahdi_cards->get_advanced('fxs_honor_mode')?>', true);
	ChangeSelectByValue('boostringer', '<?=$dahdi_cards->get_advanced('boostringer')?>', true);
	ChangeSelectByValue('fastringer', '<?=$dahdi_cards->get_advanced('fastringer')?>', true);
	ChangeSelectByValue('lowpower', '<?=$dahdi_cards->get_advanced('lowpower')?>', true);
	ChangeSelectByValue('ringdetect', '<?=$dahdi_cards->get_advanced('ringdetect')?>', true);
	ChangeSelectByValue('mwi', '<?=$dahdi_cards->get_advanced('mwi')?>', true);
	ChangeSelectByValue('echocan_nlp_type', '<?=$dahdi_cards->get_advanced('echocan_nlp_type')?>', true);
	ChangeSelectByValue('echocan_nlp_threshold', '<?=$dahdi_cards->get_advanced('echocan_nlp_threshold')?>', true);
	ChangeSelectByValue('echocan_nlp_max_supp', '<?=$dahdi_cards->get_advanced('echocan_nlp_max_supp')?>', true);

	$('#mwi').change(function(evt) {
		if ($('#mwi :selected').val() == 'neon') {
			$('.neon').show();
		} else {
			$('.neon').hide();
		}
	});
</script>
