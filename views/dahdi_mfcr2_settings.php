<h2><?php echo _('MFC/R2 Settings')?></h2>
<hr>
<table width="100%" style="text-align:left;" border="0" cellspacing="0">
    <tr>
        <td class="mfcr2-label">
            <label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_variant">
				<a href="#" class="info"><?php echo _('MFC/R2 Variant')?>:
					<span>
						<?php echo _("MFC/R2 variant, this depends on the OpenR2 supported variants. A list of values can be found by executing the openr2 command `r2test -l`") ?>
					</span>
				</a>
			</label>
        </td>
        <td>
           	<select id="editspan_<?php echo $key?>_mfcr2_variant" name="editspan_<?php echo $key?>_mfcr2_variant">
	    		<option value="AR"	<?php echo set_default($span['mfcr2_variant'], 'AR') ?>><?php echo _('Argentina')?></option>
	    		<option value="BR"  <?php echo set_default($span['mfcr2_variant'], 'BR') ?>><?php echo _('Brazil')?></option>
	    		<option value="CN"	<?php echo set_default($span['mfcr2_variant'], 'CN') ?>><?php echo _('China')?></option>
	    		<option value="CZ"	<?php echo set_default($span['mfcr2_variant'], 'CZ') ?>><?php echo _('Czech Republic')?></option>
	    		<option value="CO"	<?php echo set_default($span['mfcr2_variant'], 'CO') ?>><?php echo _('Colombia')?></option>
	    		<option value="EC"	<?php echo set_default($span['mfcr2_variant'], 'EC') ?>><?php echo _('Ecuador')?></option>
	    		<option value="ID"	<?php echo set_default($span['mfcr2_variant'], 'ID') ?>><?php echo _('Indonesia')?></option>
	    		<option value="ITU"	<?php echo set_default($span['mfcr2_variant'], 'ITU')?>><?php echo _('International Telecommunication Union')?></option>
	    		<option value="MX"	<?php echo set_default($span['mfcr2_variant'], 'MX') ?>><?php echo _('Mexico')?></option>
	    		<option value="PH"	<?php echo set_default($span['mfcr2_variant'], 'PH') ?>><?php echo _('Philippines')?></option>
	    		<option value="VE"	<?php echo set_default($span['mfcr2_variant'], 'VE') ?>><?php echo _('Venezuela')?></option>
        	</select>
        </td>
    </tr>
    <tr>
    	<td class="mfcr2-label">
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_max_ani">
    			<a href="#" class="info"><?php echo _('Max ANI') ?>:
					<span>
						<?php echo _("Max amount of ANI to ask for<br /> <strong>Default:</strong>10"); ?>
					</span>
    			</a>
    		</label>
    	</td>
    	<td>
    	    <input type="text" id="editspan_<?php echo $key?>_mfcr2_max_ani" name="editspan_<?php echo $key?>_mfcr2_max_ani" size="3" value="<?php echo $span['mfcr2_max_ani'] ?>" />
        </td>
    </tr>
    <tr>
    	<td class="mfcr2-label">
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_max_dnis">
    			<a href="#" class="info"><?php echo _('Max DNIS') ?>:
					<span>
						<?php echo _("Max amount of DNIS to ask for<br /> <strong>Default:</strong>4") ?>
					</span>
    			</a>
    		</label>
    	</td>
    	<td>
    	    <input type="text" id="editspan_<?php echo $key?>_mfcr2_max_dnis" name="editspan_<?php echo $key?>_mfcr2_max_dnis" size="3" value="<?php echo $span['mfcr2_max_dnis'] ?>" />
        </td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_get_ani_first">
    			<a href="#" class="info"><?php echo _('Get ANI Before DNIS'); ?>:
    				<span>
    					<?php echo _("Determine whether or not to get the ANI before getting DNIS.  Some telcos require ANI first some others do not care.<br /> <strong>Default:</strong>no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_get_ani_first" name="editspan_<?php echo $key?>_mfcr2_get_ani_first">
                <option value="" <?php echo set_default($span['mfcr2_get_ani_first'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_get_ani_first'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_get_ani_first'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_category">
    			<a href="#" class="info"><?php echo _('Caller Category to Send'); ?>:
    				<span>
    					<?php echo _("Usually national_subscriber works just fine.  You can also change this setting from the dialplan by setting the variable MFCR2_CATEGORY (remember to set _MFCR2_CATEGORY from originating channels). <br />MFCR2_CATEGORY will also be a variable available in your context on incoming calls set to the value received from the far end.<br /> <strong>Default:</strong>national_subscriber"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_category" name="editspan_<?php echo $key?>_mfcr2_category">
                <option value="national_subscriber" <?php echo set_default($span['mfcr2_category'],'national_subscriber'); ?>><?php echo _('national_subscriber') ?></option>
                <option value="national_priority_subscriber" <?php echo set_default($span['mfcr2_category'],'national_priority_subscriber'); ?>><?php echo _('national_priority_subscriber')?></option>
                <option value="international_subscriber" <?php echo set_default($span['mfcr2_category'],'international_subscriber'); ?>><?php echo _('international_subscriber')?></option>
                <option value="international_priority_subscriber" <?php echo set_default($span['mfcr2_category'],'international_priority_subscriber'); ?>><?php echo _('international_priority_subscriber')?></option>
                <option value="collect_call" <?php echo set_default($span['mfcr2_category'],'collect_call'); ?>><?php echo _('collect_call')?></option>
            </select>
    	</td>
    </tr>
    <tr>
        <td>
            <label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_logdir">
                <a href="#" class="info"><?php echo _('Call Logging Directory'); ?>:
                    <span>
                        <?php echo _("Logging is stored at the Asterisk logging directory specified in asterisk.conf plus mfcr2/<whatever you put here>. If you specify 'span1' here and asterisk.conf has as logging directory /var/log/asterisk then the full path to your MFC/R2 call logs will be /var/log/asterisk/mfcr2/span1 (the directory will be automatically created if not present already)"); ?>
                    </span>
                </a>
            </label>
        </td>
        <td>
            <input type="text" id="editspan_<?php echo $key?>_mfcr2_logdir" name="editspan_<?php echo $key?>_mfcr2_logdir" value="<?php echo $span['mfcr2_logdir'] ?>" />
        </td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_call_files">
    			<a href="#" class="info"><?php echo _('Call Logging Enable'); ?>:
    				<span>
    					<?php echo _("Determine whether or not to drop call files into mfcr2_logdir. (see tooltip under Call Loggin Directory. <br /> Default: no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_call_files" name="editspan_<?php echo $key?>_mfcr2_call_files">
                <option value="" <?php echo set_default($span['mfcr2_call_files'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_call_files'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_call_files'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_logging">
    			<a href="#" class="info"><?php echo _('Log Levels'); ?>:
    				<span>
    					<?php echo _("MFC/R2 valid logging values are: all, error, warning, debug, notice, cas, mf, stack, nothing<br />error,warning,debug and notice are self-descriptive. <br />'cas' is for logging ABCD CAS tx and rx<br />'mf' is for logging of the Multi Frequency tones<br /> 'stack' is for very verbose output of the channel and context call stack, only useful if you are debugging a crash or want to learn how the library works. The stack logging will be only enabled if the openr2 library was compiled with -DOR2_TRACE_STACKS. <br /> You can mix up values, like: <strong>`loglevel=error,debug,mf`</strong> to log just error, debug and multi frequency messages<br /> 'all' is a special value to log all the activity <br /> 'nothing' is a clean-up value, in case you want to not log any activity for a channel or group of channels <br /> BE AWARE that the level of output logged will ALSO depend on the value you have in logger.conf, if you disable output in logger.conf then it does not matter you specify 'all' here, nothing will be logged so logger.conf has the last word on what is going to be logged"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<input type="text" id="editspan_<?php echo $key?>_mfcr2_logging" name="editspan_<?php echo $key?>_mfcr2_logging" value="<?php echo $span['mfcr2_logging'] ?>" />
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_mfback_timeout">
    			<a href="#" class="info"><?php echo _('MF Timeout'); ?>:
    				<span>
    					<?php echo _("MFC/R2 value in milliseconds for the MF timeout. <br />Any negative value means 'default', smaller values than 500ms are not recommended and can cause malfunctioning. <br />If you experience protocol error due to MF timeout try incrementing this value in 500ms steps<br /> <strong>Default:</strong>-1"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<input type="text" id="editspan_<?php echo $key?>_mfcr2_mfback_timeout" name="editspan_<?php echo $key?>_mfcr2_mfback_timeout" size="3" value="<?php echo $span['mfcr2_mfback_timeout'] ?>" />
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_metering_pulse_timeout">
    			<a href="#" class="info"><?php echo _('Metering Pulse Time Out'); ?>:
    				<span>
    					<?php echo _("Metering pulses are sent by some telcos for some R2 variants during a call presumably for billing purposes to indicate costs, however this pulses use the same signal that is used to indicate call hangup. <br />Therefore a timeout is sometimes required to distinguish between a <strong>real</strong> hangup and a billing pulse that should not last more than 500ms. <br />If you experience call drops some minutes after being established, try setting a value of some ms here. Values greater than 500ms are not recommended. <br /> BE AWARE that choosing the proper protocol mfcr2_variant parameter implicitly sets a good recommended value for this timer.<br />Use this parameter only when you *really* want to override the default, otherwise just comment out this value or put a -1. <br /> Any negative value means 'default'.<br /> <strong>Default:</strong>-1"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<input type="text" id="editspan_<?php echo $key?>_mfcr2_metering_pulse_timeout" name="editspan_<?php echo $key?>_mfcr2_metering_pulse_timeout" size="3" value="<?php echo $span['mfcr2_metering_pulse_timeout'] ?>" />
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_allow_collect_calls">
    			<a href="#" class="info"><?php echo _('Allow Collect Calls'); ?>:
    				<span>
    					<?php echo _("Brazil uses a special calling party category for collect calls (llamadas por cobrar)<br />instead of using the operator (as in Mexico). The R2 spec in Brazil says a special GB tone should be used to reject collect calls. If you want to ALLOW collect calls specify 'yes', if you want to BLOCK collect calls then say 'no'. Default is to block collect calls. <br />(see also 'mfcr2_double_answer')<br /> <strong>Default:</strong>no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_allow_collect_calls" name="editspan_<?php echo $key?>_mfcr2_allow_collect_calls">
                <option value="" <?php echo set_default($span['mfcr2_allow_collect_calls'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_allow_collect_calls'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_allow_collect_calls'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
        <td>
            <label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_double_answer">
                <a href="#" class="info"><?php echo _('Double Answer'); ?>:
                    <span>
                        <?php echo _("This feature is related but independent of mfcr2_allow_collect_calls. <br />Some PBX's require a double-answer process to block collect calls, if you ever have problems blocking collect calls using Group B signals (mfcr2_allow_collect_calls=no) then you may want to try with mfcr2_double_answer=yes, this will cause that every answer signal is changed by answer->clear back->answer (sort of a flash) (see also 'mfcr2_allow_collect_calls')<br /> <strong>Default:</strong>no"); ?>
                    </span>
                </a>
            </label>
        </td>
        <td>
            <select id="editspan_<?php echo $key?>_mfcr2_double_answer" name="editspan_<?php echo $key?>_mfcr2_double_answer">
                <option value="" <?php echo set_default($span['mfcr2_double_answer'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_double_answer'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_double_answer'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
        </td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_immediate_accept">
    			<a href="#" class="info"><?php echo _('Immediate Accept'); ?>:
    				<span>
    					<?php echo _("This feature allows to skip the use of Group B/II signals and go directly to the accepted state for incoming calls.<br /> <strong>Default:</strong>no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_immediate_accept" name="editspan_<?php echo $key?>_mfcr2_immediate_accept">
                <option value="" <?php echo set_default($span['mfcr2_immediate_accept'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_immediate_accept'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_immediate_accept'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_accept_on_offer">
    			<a href="#" class="info"><?php echo _('Accept on Offer'); ?>:
    				<span>
    					<?php echo _("You most likely dont need this feature. Default is yes. <br />When this is set to yes, all calls that are offered (incoming calls) for which DNIS is valid (exists in extensions.conf) and also pass collect call validation will be accepted with a Group B tone (either call with charge or not, depending on mfcr2_charge_calls).<br />With this set to 'no' then the call will NOT be accepted on offered and the call will start its execution in extensions.conf without being accepted until the channel is answered (either with Answer() or any other application resulting in the channel being answered). <br />This can be set to 'no' if your telco or PBX needs the hangup cause to be set accurately. <br />When this option is set to 'no' you must explicitly accept the call with DAHDIAcceptR2Call or implicitly through the Answer() application.<br /> <strong>Default:</strong>yes"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_accept_on_offer" name="editspan_<?php echo $key?>_mfcr2_accept_on_offer">
                <option value="" <?php echo set_default($span['mfcr2_accept_on_offer'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_accept_on_offer'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_accept_on_offer'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_skip_category">
    			<a href="#" class="info"><?php echo _('Skip Category'); ?>:
    				<span>
    					<?php echo _("Skips request of calling party category and ANI. <br />You need openr2 >= 1.2.0 to use this feature<br /> <strong>Default:</strong>no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_skip_category" name="editspan_<?php echo $key?>_mfcr2_skip_category">
                <option value="" <?php echo set_default($span['mfcr2_skip_category'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_skip_category'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_skip_category'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
	<tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_forced_release">
    			<a href="#" class="info"><?php echo _('Forced Release'); ?>:
    				<span>
    					<?php echo _("Brazil uses a special signal to force the release of the line (hangup) from the backward perspective. <br />When mfcr2_forced_release=no, the normal clear back signal will be sent on hangup, which is OK for most mfcr2 variants except for the Brazilian variant where the central will leave the line up for several seconds (30, 60). <br /> This is sometimes is not what people really want. When mfcr2_forced_release=yes, a different ; signal will be sent to hangup the call indicating that the line should be released immediately.<br /> <strong>Default:</strong>no"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_forced_release" name="editspan_<?php echo $key?>_mfcr2_forced_release">
                <option value="" <?php echo set_default($span['mfcr2_forced_release'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_forced_release'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_forced_release'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_charge_calls">
    			<a href="#" class="info"><?php echo _('Charge Calls'); ?>:
    				<span>
    					<?php echo _("Toggles 'report accept call with charge'; whether or not report to the other end 'accept call with charge'.  This setting has no effect with most telcos and is usually is safe.<br />Leave the default (yes), but once in a while when interconnecting with old PBXs this may be useful. <br />Concretely this affects the Group B signal used to accept calls. <br />The application DAHDIAcceptR2Call can also be used to decide this in the dial plan in a per-call basis instead of doing it here for all calls.<br /> <strong>Default:</strong>yes"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<select id="editspan_<?php echo $key?>_mfcr2_charge_calls" name="editspan_<?php echo $key?>_mfcr2_charge_calls">
                <option value="" <?php echo set_default($span['mfcr2_charge_calls'],''); ?>></option>
                <option value="no" <?php echo set_default($span['mfcr2_charge_calls'],'no'); ?>><?php echo _('No')?></option>
                <option value="yes" <?php echo set_default($span['mfcr2_charge_calls'],'yes'); ?>><?php echo _('Yes')?></option>
            </select>
    	</td>
    </tr>
    <tr>
    	<td>
    		<label class="mfcr2-label" for="editspan_<?php echo $key?>_mfcr2_advanced_protocol_file">
    			<a href="#" class="info"><?php echo _('Advanced Protocol Configuration File'); ?>:
    				<span>
    					<?php echo _("<strong>WARNING:</strong> advanced users only as it allows you custom define tone definitions used in the protocol! <br /> This parameter is commented by default because YOU DON'T NEED IT UNLESS YOU REALLY UNDERSTAND MFC/R2!"); ?>
    				</span>
				</a>
			</label>
    	</td>
    	<td>
			<input type="text" id="editspan_<?php echo $key?>_mfcr2_advanced_protocol_file" name="editspan_<?php echo $key?>_mfcr2_advanced_protocol_file" value="<?php echo $span['mfcr2_advanced_protocol_file'] ?>" />
    	</td>
    </tr>
</table>
