<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
class Restore Extends Base\RestoreBase{

	public function runRestore($jobid){
		$configs = $this->getConfigs();
		foreach ($configs as $key => $value) {
			$this->FreePBX->Config->update($key,$value);
		}
		$files = $this->getFiles();
		foreach ($files as $file) {
			$filename = $file['pathto'] . '/' . $file['filename'];
			if(file_exists($filename)){
				rename($filename, $filename.'.bak');
			}
			copy($this->tmpdir . '/files' . $filename, $filename);
		}
		//Let the module do what it needs to do.
		$dahdiCards = new \dahdi_cards();
		$dahdiCards->load();
		$dahdiCards->checkHardware();
	}
	public function processLegacy($pdo, $data, $tables, $unknownTables, $tmpfiledir){
		$time = time();
		$tables = array_flip($tables + $unknownTables);
		if (!isset($tables['callback'])) {
			return $this;
		}
		$ampconf = $this->getAMPConf($pdo);

		$advancedSettings = [
			'DAHDIEXEC',
			'DAHDIMODULESLOC',
			'DAHDIDISABLEWRITE',
			'DAHDIMODPROBELOC',
			'DAHDIECHOCAN',
			'DAHDISYSTEMLOC',
			'DAHDIMOCKHW',
			'ZAP2DAHDICOMPAT',
		];
		foreach ($advancedSettings as $key) {
			if(isset($ampconf[$key])){
				$this->FreePBX->Config->update($key, $ampconf[$key]);
			}
		}
		$dahdidir = $tmpfiledir . '/etc/dahdi';
		if(is_dir($dahdidir)){
			$iterator = new DirectoryIterator($dahdidir);
			foreach ($iterator as $fileinfo) {
				if($fileinfo.isFile() && $fileinfo->isReadable()){
					if(!is_dir('/etc/dahdi')){
						mkdir('/etc/dahdi', 0755);
					}
					$filename = $dahdidir.'/'. $fileinfo->getFilename();
					copy($filename, '/etc/dahdi/' . $fileinfo->getFilename());
					if (is_file('/etc/dahdi/' . $fileinfo->getFilename())) {
						copy('/etc/dahdi/' . $fileinfo->getFilename(), '/etc/dahdi/' . $fileinfo . getFilename() . '.' . $time);
					}
				}
			}
		}
		$wanpipedir = $tmpfiledir . '/etc/wanpipe';
		if(is_dir($dahdidir)){
			$iterator = new DirectoryIterator($wanpipedir);
			foreach ($iterator as $fileinfo) {
				if($fileinfo.isFile() && $fileinfo->isReadable()){
					if (!is_dir('/etc/wanpipe')) {
						mkdir('/etc/wanpipe', 0755);
					}
					$filename = $wanpipedir.'/'. $fileinfo->getFilename();
					if(is_file('/etc/wanpipe/' . $fileinfo->getFilename())){
						copy('/etc/wanpipe/' . $fileinfo->getFilename(), '/etc/dahdi/' . $fileinfo . getFilename().'.'.$time);
					}
					copy($filename,'/etc/dahdi/'.$fileinfo->getFilename());
				}
			}
		}

		$dahdiCards = new \dahdi_cards();
		$dahdiCards->load();
		$dahdiCards->checkHardware();
		return $this;
	}
}
