<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
use Symfony\Component\Filesystem\Filesystem;
use SplFileInfo;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		/** Everything is dynamic so ummm so we simply save the advanced settings ¯\_(ツ)_/¯ */
		$this->addConfigs($this->getAdvanced());
	}

	public function getAdvanced(){
		$all = $this->FreePBX->Config->get_conf_settings();
		return array_filter($all, function($settings){
			return (isset($settings['module']) && $settings['module'] == 'dahdiconfig' && isset($settings['modified']) && $settings['modified']);
		});
}
}
