<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
use Symfony\Component\Filesystem\Filesystem;
use SplFileInfo;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		/** Everything is dynamic so ummm so we simply save the advanced settings ¯\_(ツ)_/¯ */
		$this->addConfigs($this->dumpAdvancedSettings());
	}
}
