<?php
namespace FreePBX\modules\__MODULENAME__;
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
            copy($this->tmpdir . '/files/' . $filename, $filename);
        }
        //Let the module do what it needs to do.
        $dahdiCards = new \dahdi_cards();
        $dahdiCards->load();
        $dahdiCards->checkHardware();
    }
    
}