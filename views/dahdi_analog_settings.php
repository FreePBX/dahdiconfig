<h2><?php echo sprintf(_('Analog %s'),(($analog_type == 'fxo')?'FXO':'FXS'))?> Ports</h2>
<hr />
<form id="dahdi_editanalog_<?php echo $analog_type?>" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=analog&amp;ports=<?php echo $analog_type?>" method="post">
	<div id="editanalog_options_container">
		<table>
		<?php
			$spans = ($analog_type == 'fxo') ? $dahdi_cards->get_fxo_ports() : $dahdi_cards->get_fxs_ports();
			$lsports = $dahdi_cards->get_ls_ports();
			foreach ($spans as $p) { ?>
				<?php $port = $dahdi_cards->get_port($p); ?>
				<tr> <!-- row 1 -->
		    		<td colspan="2"><h3><?php echo sprintf(_('Port %s Settings'),$p)?>:</h3></td>
		    	</tr>
				<tr> <!-- row 2 -->
		            <td>
		            	<a href="#" class="info"><?php echo _('Signaling')?>:
		            		<span><?php echo _('This option allows you to specify the type signaling for this analog device.')?>:
				                <ul>
				                    <li><?php echo _('Kewl Start - Coined term for an extension of loop start (FXO and FXS) signaling which adds disconnect supervision through the use of an open switching interval (OSI). In addition to the operation afforded by loop start, the CO (central office) signals the terminal (user) end that the distant party has hung up. The CO switch will remove battery voltage from the loop for about 250 ms, within 6 seconds after the far-end party disconnects.')?></li>
				                    <li><?php echo _('Loop Start - When idle, or on-hook, the loop potential is held a nominal 48V DC, provided by the telephone exchange or a foreign exchange station (FXS) interface. When a terminal initiates use the line, it causes current to flow by closing the loop, and this signals the FXS end to provide dial tone on the line and to expect dial signals, in form of DTMF digits or dial pulses, or a hook flash. When the loop is opened and current stops flowing, the subscriber equipment signals that it has finished using the line; the telephone exchange resets the line to an idle state.')?></li>
				                </ul>
		            		</span>
		        		</a>
		        	</td>
		            <td>
		                <select name="<?php echo $analog_type?>_port_<?php echo $p?>" id="<?php echo $analog_type?>_port_<?php echo $p?>">
		                    <option value="ks" <?php echo (in_array($p, $lsports)) ? '' : 'selected'; ?>><?php echo _('Kewl Start')?></option>
		                    <option value="ls" <?php echo (in_array($p, $lsports)) ? 'selected' : ''; ?>><?php echo _('Loop Start')?></option>
		                </select>
		            </td>
		    	</tr>
				<tr> <!-- row 3 -->
		            <td>
		            	<a href="#" class="info"><?php echo _('Group')?>:
		            		<span><?php echo _('A group ID number to associate this analog device with when referencing it elswhere using g<num>')?></span>
		            	</a>
		            </td>
		            <td>
		            	<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_group" id="<?php echo $analog_type?>_port_<?php echo $p?>_group" size="2" value="<?php echo $port['group']?>" />
		            </td>
		    	</tr>

				<?php if ($analog_type == 'fxo') { ?>
		        <tr> <!-- row 4 -->
		            <td>
		            	<a href="#" class="info"><?php echo _('Context')?>:
		            		<span><?php echo _('The context to use for inbound calls to this analog device')?></span>
		            	</a>
		            </td>
					<td>
						<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_context" id="<?php echo $analog_type?>_port_<?php echo $p?>_context" value="<?php echo $port['context']?>" />
		            </td>
		        </tr>
		        <?php } ?> <!-- if $analog_type end -->


				<?php
				$port_custom_settings = $port['custom'];
        		// If there are no custom settings yet, initialize as
        		// array of empty strings so we always have at least one
        		// blank custom setting in the form (the first one)
        		if (!is_array($port_custom_settings) || count($port_custom_settings) == 0) {
        			$port_custom_settings = array('' => '');
        		}

        		$id = 0;
        		foreach ($port_custom_settings as $key => $val) {
        			?>
					<tr id="dh_analog_<?php echo $p; ?>_custom_<?php echo $id; ?>">
					    <td>
					        <?php
					        if ($id == 0) {
						        echo _('Other Port Settings') . ":";
					        }
					        ?>
					    </td>
					    <td>
					        <a href="#" onclick="dh_analog_delete_field(<?php echo $p; ?>, <?php echo $id; ?>)"><img height="10px" src="images/trash.png"></a>
					        <input type="hidden" name="dh_analog_<?php echo $p; ?>_custom_ids[]" value="<?php echo $id; ?>" />
					        <input type="hidden" id="dh_analog_<?php echo $p; ?>_origsetting_key_<?php echo $id; ?>" name="dh_analog_<?php echo $p; ?>_origsetting_key_<?php echo $id; ?>" value="<?php echo $key?>" />
					        <input id="dh_analog_<?php echo $p; ?>_setting_key_<?php echo $id; ?>" name="dh_analog_<?php echo $p; ?>_setting_key_<?php echo $id; ?>" value="<?php echo $key?>" /> =
					        <input id="dh_analog_<?php echo $p; ?>_setting_val_<?php echo $id; ?>" name="dh_analog_<?php echo $p; ?>_setting_val_<?php echo $id; ?>" value="<?php echo $val?>" /> <br />
					    </td>
					</tr>
	        		<?php
	        		$id++;
        		}

        		?>
				<tr id="dh_analog_add_to_<?php echo $p; ?>"> <!-- last row -->
				    <td>
				    </td>
				    <td>
				        <a style="cursor: pointer;" onclick="dh_analog_add_field(<?php echo $p; ?>, <?php echo $a?>)"><img src="assets/dahdiconfig/images/add.png"></a>
				    </td>
				</tr>
		        <!--
				<a href="#" class="info">Receive Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
		        <input type="text" name="editspan_<?php echo $key?>_rxgain" id="editspan_<?php echo $key?>_rxgain" value="<?php echo $span['rxgain']; ?>">
				<label for="editspan_<?php echo $key?>_txgain"><a href="#" class="info">Transmit Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
				<input type="text" name="editspan_<?php echo $key?>_txgain" id="editspan_<?php echo $key?>_txgain" value="<?php echo $span['txgain']; ?>">
				-->
			<?php } ?> <!-- foreach $spans end -->
		</table>
	</div>
</form>
