<h2><?php echo _('Sangoma Settings')?></h2>
<h3><?php echo sprintf(_('This edits all settings in %s'),'/etc/wanpipe/global.conf')?></h3>
</hr>
<table width="100%" style="text-align:left;">
	<tr>
		<td style="width:10px;">
			<label for="sangoma_dahdimode"><?php echo _('Run Wanpipe In DAHDI/DIGIUM Mode') ?></label>
		</td>
		<td>
			<select name="sangoma_dahdimode" id="sangoma_dahdimode" class="form-control">
				<option value="no" <?php echo $settings['dahdimode'] == 'no' ? 'selected' : ''?>><?php echo _('No')?></option>
				<option value="yes" <?php echo $settings['dahdimode'] == 'yes' ? 'selected' : ''?>><?php echo _('Yes')?></option>
			</select>
		</td>
	</tr>
	<tr id="sangoma_t1mode" style="<?php echo ($settings['dahdimode'] == 'yes') ? '' : 'hidden'?>">
		<td style="width:10px">
			<label for="sangoma_t1mode"><?php echo _("T1/Mode")?>:</label>
		</td>
		<td>
			<select name="sangoma_t1mode" class="form-control">
				<option value="T1" <?php echo $settings['t1mode'] == 'T1' ? 'selected' : ''?>>T1</option>
				<option value="E1" <?php echo $settings['t1mode'] == 'E1' ? 'selected' : ''?>>E1</option>
			</select>
		</td>
	</tr>
  <tr>
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_FE_CLOCK"><?php echo _('Set wanpipe port clocking') ?></label>
    </td>
    <td>
      <select name="sangoma_WANPIPE_GLOBAL_FE_CLOCK" id="sangoma_WANPIPE_GLOBAL_FE_CLOCK" class="form-control">
        <option value="NORMAL" <?php echo $settings['WANPIPE_GLOBAL_FE_CLOCK'] == 'NORMAL' ? 'selected' : ''?>><?php echo _('Normal')?></option>
        <option value="MASTER" <?php echo $settings['WANPIPE_GLOBAL_FE_CLOCK'] == 'MASTER' ? 'selected' : ''?>><?php echo _('Master')?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_HW_DTMF"><?php echo _('Set wanpipe port clocking') ?></label>
    </td>
    <td>
      <select name="sangoma_WANPIPE_GLOBAL_HW_DTMF" id="sangoma_WANPIPE_GLOBAL_HW_DTMF" class="form-control">
        <option value="YES" <?php echo $settings['WANPIPE_GLOBAL_HW_DTMF'] == 'YES' ? 'selected' : ''?>><?php echo _('Yes')?></option>
        <option value="NO" <?php echo $settings['WANPIPE_GLOBAL_HW_DTMF'] == 'NO' ? 'selected' : ''?>><?php echo _('No')?></option>
      </select>
    </td>
  </tr>
  <tr>
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY"><?php echo _('Enable DTMF CID') ?></label>
    </td>
    <td>
      <select name="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY" id="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY" class="form-control">
        <option value="NO" <?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY'] == 'NO' ? 'selected' : ''?>><?php echo _('No')?></option>
        <option value="YES" <?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY'] == 'YES' ? 'selected' : ''?>><?php echo _('Yes')?></option>
      </select>
    </td>
  </tr>
  <tr class="dtmf-cid <?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY'] == 'NO' ? 'hidden' : ''?>">
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_THRESHOLD"><?php echo _('Polarity Threshold') ?></label>
    </td>
    <td>
      <input type="text" name="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_THRESHOLD" id="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_THRESHOLD" class="form-control" value="<?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY_THRESHOLD']?>">
    </td>
  </tr>
  <tr class="dtmf-cid <?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY'] == 'NO' ? 'hidden' : ''?>">
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMER"><?php echo _('Polarity CID Timer') ?></label>
    </td>
    <td>
      <input type="text" name="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMER" id="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMER" class="form-control" value="<?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMER']?>">
    </td>
  </tr>
  <tr class="dtmf-cid <?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY'] == 'NO' ? 'hidden' : ''?>">
    <td style="width:10px;">
      <label for="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMEOUT"><?php echo _('Polarity CID Timeout') ?></label>
    </td>
    <td>
      <input type="text" name="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMEOUT" id="sangoma_WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMEOUT" class="form-control" value="<?php echo $settings['WANPIPE_GLOBAL_FAKE_POLARITY_CIDTIMEOUT']?>">
    </td>
  </tr>
</table>
<script>
  $('#sangoma_WANPIPE_GLOBAL_FAKE_POLARITY').change(function() {
    val = $(this).val()
    if(val == 'YES') {
      $('.dtmf-cid').removeClass("hidden");
    } else {
      $('.dtmf-cid').addClass("hidden");
    }
  });
	$('#sangoma_dahdimode').change(function() {
		val = $(this).val()
		if(val == 'yes') {
			$('#sangoma_t1mode').removeClass("hidden");
		} else {
			$('#sangoma_t1mode').addClass("hidden");
		}
	});
</script>
