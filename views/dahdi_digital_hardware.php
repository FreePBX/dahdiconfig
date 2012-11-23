<h2>Digital Hardware</h2>
<hr />
<table class="taglist" id="digital_cards_table" cellpadding="5" cellspacing="1" border="0">
        <thead>
        <tr>
                <th>Span</th>
                <th>Alarms</th>
                <th>Framing/Coding</th>
                <th>Channels Used/Total</th>
                <th>Signalling</th>
                <th>Action</th>
        </tr>
        </thead>
        <tbody>
	<?php $ctr = 1;
	foreach($dahdi_cards->get_spans() as $key=>$span) {
		$name_split = explode('/', $span['name']);
		$devicetype = $span['devicetype'];
		$name = "{$devicetype}, Card ".($name_split[1]+1)." - Port {$name_split[2]} (span_{$key})";
	?>
	<tr class="<?php echo ((($ctr % 2) != 0)?"odd":"")?>">
		<td><?php echo $name?></td>
		<td><?php echo $span['alarms']?></td>
		<td><?php echo $span['framing']."/".$span['coding']?></td>
		<td><?php echo $span['totchans']."/".$span['totchans']?></td>
		<td><?php echo ((isset($span['signalling']))?$span['signalling']:"Not Yet Defined")?></td>
		<td><a href="config.php?type=setup&display=dahdi&dahdi_form=digital_span&span=<?php echo $key?>">Edit</a></td>
	</tr>
	<?php $ctr++;
	} ?>
        </tbody>
</table>
