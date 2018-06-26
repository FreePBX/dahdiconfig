<?php
namespace FreePBX\modules\Dahdiconfig;
use FreePBX\modules\Backup as Base;
class Backup Extends Base\BackupBase{
  public function runBackup($id,$transaction){
    $this->dirs = [];
    /** Everything is dynamic so ummm so we simply save the advanced settings ¯\_(ツ)_/¯ */
    $this->addConfigs($this->getAdvanced());
    $fileList = [
        '/etc/dahdi/system.conf',
        '/etc/dahdi/genconf_parameters',
        '/etc/dahdi/modules',
        '/etc/wanpipe/wanrouter.rc',
    ];
    foreach ($fileList as $file) {
        if(file_exists($file)){
            $fObj = new \SplFileInfo($file);
            if(!$fObj->isReadable()){
                continue;
            }
            $this->dirs[$fObj->getPath()] = $fObj->getPath();
            $this->addFile($fObj->getBasename(), $fObj->getPath, '', "config");
        }
    }
    $this->addDirectories($dirs);
  }

  public function getAdvanced(){
    $all = $this->FreePBX->Config->get_conf_settings();
    return array_filter($all, function($settings){
        return (isset($settings['module']) && $settings['module'] == 'dahdiconfig' && isset($settings['modified']) && $settings['modified']);
    });
  }
}