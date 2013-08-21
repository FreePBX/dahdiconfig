<h2>Analog Hardware</h2>
<hr style="width: 75%; margin-left:0;" />
<table class="alt_table" id="digital_cards_table" cellpadding="5" cellspacing="1" border="0">
        <thead>
        <tr>
                <th>Type</th>
                <th>Ports</th>
                <th>Action</th>
        </tr>
        </thead>
        <tbody>
	<?php
		$fxo = $dahdi_cards->get_fxo_ports();
		$fxs = $dahdi_cards->get_fxs_ports();
	?>
	<tr>
		<td>FXO Ports</td>
		<td><?php 
		$c = count($fxo);
		if($c) {
    		$i = 1;
    		foreach($fxo as $chan) {
    		    echo $chan;
    		    echo ($c != $i) ? "," : "";
    		    $i++;
    		}
	    } else {
	        echo "--";
	    }
		?></td>
		<td><?php echo ((count($fxo))?'<a href="#" onclick="dahdi_modal_settings(\'analog\',\'fxo\');">Edit</a>':'')?></td>
	</tr>
	<tr>
		<td>FXS Ports</td>
		<td><?php echo ((count($fxs))?implode(',', $fxs):'--')?></td>
		<td><?php echo ((count($fxs))?'<a href="#" onclick="dahdi_modal_settings(\'analog\',\'fxs\');">Edit</a>':'')?></td>
	</tr>
        </tbody>
</table>
