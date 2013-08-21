<form id="form-modules" action="config.php?quietmode=1&amp;handler=file&amp;module=dahdiconfig&amp;file=ajax.html.php&amp;type=modulessubmit">
	<div id="modules">
		<h2>DAHDi Module Load/Order</h2>
		<h3>This edits order and loading of DAHDi modules in /etc/dahdi/modules</h3>
		<ul class="modules-sortable" id="modules-sortable">
		<?php 
		$mod_id = 0;
		foreach($mods as $modules => $info) {?>
			<li id="mod-<?php echo ($info['type'] == 'ud') ? 'ud-'.$mod_id : $modules ?>">
				<?php if($info['type'] == 'sys') {?>
					<input type="checkbox" id="input-<?php echo $modules?>" value="on" <?php echo $info['status'] ? 'checked' : ''?>><?php echo $modules?>
				<?php } elseif($info['type'] == 'ud') {?>
					<input type="checkbox" id="mod-ud-checkbox-<?php echo $mod_id?>" value="on" <?php echo $info['status'] ? 'checked' : ''?>><img style="cursor: pointer;" height="10px" src="images/trash.png" onclick="mods_del_field('mod-ud-<?php echo $mod_id?>')"><input type="textbox" id="mod-ud-name-<?php echo $mod_id?>" value="<?php echo $modules?>">
				<?php $mod_id++; } ?>
				<br/>
			</li>
		<?php } ?>
			<li id="mod-ud-<?php echo $mod_id?>">
				<input type="checkbox" id="mod-ud-checkbox-<?php echo $mod_id?>"><img style="cursor: pointer;" height="10px" src="images/trash.png" onclick="mods_del_field('mod-ud-<?php echo $mod_id?>')"><input type="textbox" id="mod-ud-name-<?php echo $mod_id?>" value="">
			</li>
		</ul>
		<a style="cursor: pointer;" onclick="mods_add_field()"><img src="assets/dahdiconfig/images/add.png"></a>
	</div>
	<input type="hidden" id="mods_add_id" value"<?php echo $mod_id + 1?>">
</form>

<script>
	var mods_add_id = $('#mods_add_id').val();
	function mods_add_field() {
		$('#modules-sortable li:last').after('<li id="mod-ud-'+mods_add_id+'"><input type="checkbox" id="mod-ud-checkbox-'+mods_add_id+'"><a><img height="10px" style="cursor: pointer;" src="images/trash.png" onclick="mods_del_field(\'mod-ud-'+mods_add_id+'\')"></a><input type="textbox" id="mod-ud-name-'+mods_add_id+'" value=""></li>');
		mods_add_id++
		$('.modules-sortable').sortable('destroy');
		$('.modules-sortable').sortable();
	}
	
	function mods_del_field(id) {
		$('#'+id).remove();
	}
</script>