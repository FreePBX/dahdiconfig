<h2>Analog <?php echo (($analog_type == 'fxo')?'FXO':'FXS')?> Ports</h2>
<hr />
<form id="dahdi_editanalog_<?php echo $analog_type?>" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=analog&amp;ports=<?php echo $analog_type?>" method="post">
<div id="editanalog_options_container">
	<table>
<?php
	$spans = ($analog_type == 'fxo') ? $dahdi_cards->get_fxo_ports() : $dahdi_cards->get_fxs_ports();
	$lsports = $dahdi_cards->get_ls_ports();
	foreach ($spans as $p) { ?>
	<?php $port = $dahdi_cards->get_port($p); ?>
	<tr>
		<td colspan="2"><h3>Port <?php echo $p?> Settings:</h3></td>
	</tr>
	<tr>
		<td><a href="#" class="info">Signaling:<span>fill</span></a></td>
		<td>
			<select name="<?php echo $analog_type?>_port_<?php echo $p?>" id="<?php echo $analog_type?>_port_<?php echo $p?>">
				<option value="ks" <?php echo (in_array($p, $lsports)) ? '' : 'selected'; ?>>Kewl Start</option>
				<option value="ls" <?php echo (in_array($p, $lsports)) ? 'selected' : ''; ?>>Loop Start</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><a href="#" class="info">Group:<span>fill</span></a></td>
		<td>
			<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_group" id="<?php echo $analog_type?>_port_<?php echo $p?>_group" size="2" value="<?php echo $port['group']?>" />
		</td>
	</tr>
		<?php if ($analog_type == 'fxo') { ?>
			<tr>
				<td><a href="#" class="info">Context:<span>fill</span></a></td>
				<td>
					<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_context" id="<?php echo $analog_type?>_port_<?php echo $p?>_context" value="<?php echo $port['context']?>" />
				</td>
			</tr>
		<?php } ?>
		<!--
		<a href="#" class="info">Receive Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
        <input type="text" name="editspan_<?php echo $key?>_rxgain" id="editspan_<?php echo $key?>_rxgain" value="<?php echo $span['rxgain']; ?>">
		<label for="editspan_<?php echo $key?>_txgain"><a href="#" class="info">Transmit Gain<span>The values are in db (decibels). A positive number increases the volume level on a channel, and a negative value decreases volume level.</span></a></label>
		<input type="text" name="editspan_<?php echo $key?>_txgain" id="editspan_<?php echo $key?>_txgain" value="<?php echo $span['txgain']; ?>">
		-->
	<?php } ?>
</table>
</div>
</form>
