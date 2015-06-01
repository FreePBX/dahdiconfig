
<table data-maintain-selected="true" data-show-columns="true" data-show-toggle="true" data-toggle="table" data-pagination="true" data-search="true"  id="analog_cards_table">
        <thead>
        <tr>
                <th><?php echo _('Type')?></th>
                <th><?php echo _('Ports')?></th>
                <th><?php echo _('Action')?></th>
        </tr>
        </thead>
        <tbody>
	<?php
		$fxo = $dahdi_cards->get_fxo_ports();
		$fxs = $dahdi_cards->get_fxs_ports();
	?>
	<tr>
		<td><?php echo sprintf(_('%s Ports'),'FXO')?></td>
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
		<td><?php echo ((count($fxo))?'<a href="#" onclick="dahdi_modal_settings(\'analog\',\'fxo\');">'._('Edit').'</a>':'')?></td>
	</tr>
	<tr>
		<td><?php echo sprintf(_('%s Ports'),'FXS')?></td>
		<td><?php echo ((count($fxs))?implode(',', $fxs):'--')?></td>
		<td><?php echo ((count($fxs))?'<a href="#" onclick="dahdi_modal_settings(\'analog\',\'fxs\');">'._('Edit').'</a>':'')?></td>
	</tr>
        </tbody>
</table>
