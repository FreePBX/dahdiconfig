<h2>Digital Hardware</h2>
<hr width="80%" style="text-align:left;margin-left:0"/>
<table class="alt_table" id="digital_cards_table" cellpadding="5" cellspacing="1" border="0">
        <thead>
        <tr>
                <th>Span</th>
                <th>Alarms</th>
                <th>Framing/Coding</th>
                <th>Channels Used/Total</th>
                <th>D-Channel</th>
                <th>Signaling</th>
                <th>Action</th>
        </tr>
        </thead>
        <tbody>
	<?php $ctr = 1;
	foreach($dahdi_cards->get_spans() as $key=>$span) {
		$name_split = explode('/', $span['name']);
		$devicetype = $span['devicetype'];
		$name = "{$span['manufacturer']} - {$span['description']} [{$span['dsid']}]";
	?>
	<tr>
		<td><?php echo $name?></td>
		<td id="digital_alarms_<?php echo $key; ?>_label"><?php echo $span['alarms']?></td>
		<td id="digital_framingcoding_<?php echo $key; ?>_label"><?php echo $span['framing']."/".$span['coding']?></td>
		<td id="digital_totchans_<?php echo $key; ?>_label"><?php echo $span['totchans']."/".$span['totchans']?></td>
		<td id="digital_dchan_<?php echo $key; ?>_label"><?php echo ((isset($span['reserved_ch']))?$span['reserved_ch']:"Not Yet Defined")?></td>
		<td id="digital_signalling_<?php echo $key; ?>_label"><?php echo ((isset($span['signalling']))?$span['signalling']:"Not Yet Defined")?></td>
		<td><a href="#" onclick="dahdi_modal_settings('digital','<?php echo $key?>');">Edit</a></td>
	</tr>
	<?php $ctr++;
	} ?>
        </tbody>
</table>
