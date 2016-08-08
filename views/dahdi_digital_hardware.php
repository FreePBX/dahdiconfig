
<table	data-toolbar="#toolbar-digital" data-url="ajax.php?module=dahdiconfig&command=digitalspans" data-maintain-selected="true" data-show-columns="true" data-show-toggle="true" data-toggle="table" data-pagination="true" data-search="true"	id="digital_cards_table">
	<thead>
		<tr>
			<th data-field = "dsid" data-formatter="spanFormatter"><?php echo _('Span')?></th>
			<th data-field = "alarms"><?php echo _('Alarms')?></th>
			<th data-formatter = "framingFormatter"><?php echo _('Framing/Coding')?></th>
			<th data-formatter = "channelFormatter"><?php echo _('Channels Used/Total')?></th>
			<th data-field = "reserved_ch"><?php echo _('D-Channel')?></th>
			<th data-field="signalling" data-formatter="signallingFormatter"><?php echo _('Signaling')?></th>
			<th data-formatter = "actionFormatter"><?php echo _('Action')?></th>
		</tr>
	</thead>
</table>
<script>
function spanFormatter(value, row, index){
  return row['manufacturer']+" - "+row['description']+" [ "+row['dsid']+"]";
}
function framingFormatter(value, row, index){
	return row['framing']+'/'+row['coding'];
}
function channelFormatter(value,row,index){
	return row['totchans']+'/'+row['definedchans'];
}
function actionFormatter(value, row, index){
	return '<a href="#" onclick="dahdi_modal_settings(\'digital\',\''+row['dsid']+'\');">'+ _('Edit')+'</a>';
}
function signallingFormatter(v){
  if(v == ''){
    return _("Not Yet Defined");
  }else{
    return v;
  }
}
</script>
