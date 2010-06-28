<form name="dahdi_editspan" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
<h2>Span: <span id="span"><?=$span['description']?></span></h2>
<input type="hidden" name="editspan_span" value="<?=$_GET['span']/* I'm aware this is xss-vulnerable, but this is suppose to be a safe admin site. */?>" />
<hr />
<div class="setting">
	<label for="editspan_alarm">Alarms:</label>
	<span id="editspan_alarms" name="editspan_alarms"><?=$span['alarms']?></span>
</div>
<div class="setting">
	<label for="editspan_framing">Framing/Coding:</label>
	<select id="editspan_fac" name="editspan_fac">
	<? switch($span['totchans']) {
	   case 3: ?>
		<option value="CCS/AMI"></option>
	<? 	break;
	   case 24: ?>
		<option value="ESF/B8ZS">ESF/B8ZS</option>
		<option value="D4/AMI">D4/AMI</option>
	<? 	break;
	   case 31: ?>
		<option value="CCS/HDB3">CCS/HDB3</option>
		<option value="CCS/HDB3/CRC4">CCS/HDB3/CRC4</option>
	   <?	break;
	   default:
	   	break;
	} ?>
	</select>
</div>
<div class="setting">
	<label for="editspan_channels">Channels:</label>
	<span id="editspan_channels"></span>
</div>
<div class="setting">
	<label for="editspan_signalling">Signalling:</label>
	<select id="editspan_signalling" name="editspan_signalling">
		<option value="pri_net">PRI - Net</option>
		<option value="pri_cpe">PRI - CPE</option>
		<option value="em">E & M</option>
		<option value="em_w">E & M -- Wink</option>
		<option value="featd">E & M -- fead(DTMF)</option>
		<option value="fxo_ks">FXOKS</option>
		<option value="fxo_ls">FXOLS</option>
	</select>
</div>
<? if ($span['totchans'] != 3 || substr($span['signalling'],0,3) == 'pri'): ?>
<div class="setting" id="switchtype">
	<label for="editspan_switchtype">Switchtype:</label>
	<select id="editspan_switchtype" name="editspan_switchtype">
		<option value="national">National ISDN 2 (default)</option>
		<option value="dms100">Nortel DMS100</option>
		<option value="4ess">AT&T 4ESS</option>
		<option value="5ess">Lucent 4ESS</option>
		<option value="euroisdn">EuroISDN</option>
		<option value="ni1">Old National ISDN 1</option>
		<option value="qsig">Q.SIG</option>
	</select>
</div>
<? endif; ?>
<div class="setting">
	<label for="editspan_syncsrc">Sync/Clock Source:</label>
	<select id="editspan_syncsrc" name="editspan_syncsrc">
	<? for($i=0; $i<$dahdi_cards->get_span_count($span['location']); $i++): ?>
		<option value="<?=$i?>"><?=$i?></option>
	<? endfor; ?>
	</select>
</div>
<div class="setting">
	<label for="editspan_lbo">Line Build Out:</label>
	<select id="editspan_lbo" name="editspan_lbo">
		<option value="0">0 db (CSU)/0-133 feet (DSX-1)</option>
		<option value="1">133-266 feet (DSX-1)</option>
		<option value="2">266-399 feet (DSX-1)</option>
		<option value="3">399-533 feet (DSX-1)</option>
		<option value="4">533-655 feet (DSX-1)</option>
		<option value="5">-7.5db (CSU)</option>
		<option value="6">-15db (CSU)</option>
		<option value="7">-22.5db (CSU)</option>
	</select>
</div>
<div class="setting">
	<label for="editspan_pridialplan">Pridialplan:</label>
	<select id="editspan_pridialplan" name="editspan_pridialplan">
		<option value="national">National</option>
		<option value="dynamic">Dynamic</option>
		<option value="unknown">Unknown</option>
		<option value="local">Local</option>
		<option value="private">Private</option>
		<option value="international">International</option>
	</select>
</div>
<div class="setting">
	<label for="editspan_prilocaldialplan">Prilocaldialplan:</label>
	<select id="editspan_prilocaldialplan" name="editspan_prilocaldialplan">
		<option value="national">National</option>
		<option value="dynamic">Dynamic</option>
		<option value="unknown">Unknown</option>
		<option value="local">Local</option>
		<option value="private">Private</option>
		<option value="international">International</option>
	</select>
</div>
<div class="setting">
	<label for="editspan_group">Group: </label>
	<input type="text" id="editspan_group" name="editspan_group" size="2" value="<?=($_GET['span']-1)?>" />
</div>
<div class="setting">
	<label for="editspan_context">Context: </label>
	<input type="text" id="editspan_context" name="editspan_context" value="from-pstn" />
</div>
<div class="setting">
	<label for="editspan_definedchans">Channels: </label>
	<select id="editspan_definedchans" name="editspan_definedchans">
	<? for($i=0; $i<=$span['totchans']; $i++): ?>
		<option value="<?=$i?>"><?=$i?></option>
	<? endfor; ?>
	</select>
	From: <span id="editspan_from"></span>
	Reserved: <span id="editspan_reserved"></span>
</div>
<div id="editspans_button">
	<input type="submit" id="editspan_cancel" name="editspan_cancel" value="Cancel" />
	<input type="submit" id="editspan_submit" name="editspan_submit" value="Submit" />
</div>
</form>
<script>
window.onload = function () {
	ChangeSelectByValue('editspan_fac', "<?=$span['framing']."/".$span['coding']?>", true);
	document.getElementById('editspan_channels').innerHTML = "<?="{$span['definedchans']}/{$span['totchans']} ({$span['spantype']})"?>";
	ChangeSelectByValue('editspan_signalling', "<?=$span['signalling']?>", true);
	ChangeSelectByValue('editspan_switchtype', "<?=$span['switchtype']?>", true);
	ChangeSelectByValue('editspan_pridialplan', "<?=$span['pridialplan']?>", true);
	ChangeSelectByValue('editspan_prilocaldialplan', "<?=$span['prilocaldialplan']?>", true);
	if ("<?=$span['group']?>" != "") {
		$('#editspan_group').val("<?=$span['group']?>");
	}
	if ("<?=$span['context']?>" != "") {
		$('#editspan_context').val("<?=$span['context']?>");
	}
	ChangeSelectByValue('editspan_syncsrc', "<?=$span['syncsrc']?>", true);
	ChangeSelectByValue('editspan_lbo', "<?=$span['lbo']?>", true);
	document.getElementById('editspan_from').innerHTML = "<?=$dahdi_cards->calc_bchan_fxx($_GET['span'])?>";
	document.getElementById('editspan_reserved').innerHTML = "<?=$span['reserved_ch']?>";
}
	ChangeSelectByValue('editspan_definedchans', "<?=$span['definedchans']?>", true);
</script>
