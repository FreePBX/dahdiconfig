<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{
	public function runRestore(){
		$configs = $this->getConfigs();
		$this->importAdvancedSettings($configs);
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables){
		$this->restoreLegacySettings($pdo);
	}
}
