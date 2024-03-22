<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
use Symfony\Component\Process\Process;
class Restore Extends Base\RestoreBase{
	public function runRestore(){
		$configs = $this->getConfigs();
		$this->importAdvancedSettings($configs);

		if(isset($configs['tables'])){
			$this->importTables($configs['tables']);
		} else {
			$files = $this->getFiles();
			if(empty($files[0])) {
				return;
			}
			$this->restorefromsql($files[0]);
		}
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables){
		$this->restoreLegacySettings($pdo);
	}

	private function restorefromsql($dump){
		$dumpfile = $this->tmpdir . '/files/' . ltrim($dump->getPathTo(), '/') . '/' . $dump->getFilename();
		if (!file_exists($dumpfile)) {
			return;
		}
		$this->log(_(" Restoring Data from SQL file"));
		global $amp_conf;
		$dbuser = $amp_conf['AMPDBUSER'];
		$dbpass = $amp_conf['AMPDBPASS'];
		$dbname = $amp_conf['AMPDBNAME'];
		$mysql = fpbx_which('mysql');
		$restore = "{$mysql} -u{$dbuser} -p{$dbpass} {$dbname} < {$dumpfile}";
		$process = \freepbx_get_process_obj($restore);
		$process->mustRun();
	}
}
