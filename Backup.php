<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
use Symfony\Component\Filesystem\Filesystem;
use SplFileInfo;
class Backup Extends Base\BackupBase{
	public function runBackup($id,$transaction){
		/** Everything is dynamic so ummm so we simply save the advanced settings ¯\_(ツ)_/¯ */
		$this->addConfigs($this->dumpAdvancedSettings());

		$tables = $this->getTablenames();
		$moduletables = implode(' ', $tables);
		$this->dumpTableIntoFile('dahdiconfig', $moduletables, false, false);
	}

	private function getTablenames() {
		$tables = ['dahdi_analog_custom','dahdi_advanced_modules','dahdi_modules','dahdi_advanced','dahdi_spans','dahdi_analog','dahdi_configured_locations'];
		return $tables;
	}
}
