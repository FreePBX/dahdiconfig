<h2>Analog <?php echo (($analog_type == 'fxo')?'FXO':'FXS')?> Ports</h2>
<hr />
<form id="dahdi_editanalog_<?php echo $analog_type?>" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=analog&amp;ports=<?php echo $analog_type?>" method="post">
<div id="editanalog_options_container">
<?php
	$spans = ($analog_type == 'fxo') ? $dahdi_cards->get_fxo_ports() : $dahdi_cards->get_fxs_ports();
	$lsports = $dahdi_cards->get_ls_ports();
	foreach ($spans as $p): ?>
	<?php $port = $dahdi_cards->get_port($p); ?>
	<div>
		<h3>Port <?php echo $p?> Settings:</h3>
		<a href="#" class="info">Signaling:<span>fill</span></a><select name="<?php echo $analog_type?>_port_<?php echo $p?>" id="<?php echo $analog_type?>_port_<?php echo $p?>">
			<option value="ks" <?php echo (in_array($p, $lsports)) ? '' : 'selected'; ?>>Kewl Start</option>
			<option value="ls" <?php echo (in_array($p, $lsports)) ? 'selected' : ''; ?>>Loop Start</option>
		</select><br />
		<a href="#" class="info">Group:<span>fill</span></a>
		<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_group" id="<?php echo $analog_type?>_port_<?php echo $p?>_group" size="2" value="<?php echo $port['group']?>" /><br />
		<?php if ($analog_type == 'fxo'): ?>
		<a href="#" class="info">Context:<span>fill</span></a>
		<input type="text" name="<?php echo $analog_type?>_port_<?php echo $p?>_context" id="<?php echo $analog_type?>_port_<?php echo $p?>_context" value="<?php echo $port['context']?>" />
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
</div>
</form>
