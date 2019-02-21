<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{

	public function runRestore($jobid){
		$configs = $this->getConfigs();
		foreach ($configs as $key => $value) {
			$this->FreePBX->Config->update($key,$value);
		}
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables){
		$advanced = [
			'DAHDIEXEC',
			'DAHDIMODULESLOC',
			'DAHDIDISABLEWRITE',
			'DAHDIMODPROBELOC',
			'DAHDIECHOCAN',
			'DAHDISYSTEMLOC',
			'DAHDIMOCKHW',
			'ZAP2DAHDICOMPAT',
		];
		foreach ($advanced as $key) {
			if(isset($data['settings'][$key])){
				$this->FreePBX->Config->update($key, $data['settings'][$key]);
			}
		}

		return $this;
	}
}
