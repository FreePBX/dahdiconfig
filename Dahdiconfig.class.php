<?php
// vim: set ai ts=4 sw=4 ft=php:
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2014 Schmooze Com Inc.
//
namespace FreePBX\modules;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class Dahdiconfig extends \FreePBX_Helpers implements \BMO {
	private $message = '';
	private $lookupCache = array();
	private $contactsCache = array();

	public function __construct($freepbx = null) {
		$this->db = $freepbx->Database;
		$this->freepbx = $freepbx;
	}

	public function install() {

	}
	public function uninstall() {

	}
	public function backup(){

	}
	public function restore($backup){

	}
	public function doConfigPageInit($page){}

	/**
	 * Is Wanrouter adding
	 * @return boolean True if modules loaded and running, false otherwise
	 */
	public function wanrouterRunning() {
		if(!file_exists('/proc/net/wanrouter/status')) {
			return false;
		}
		$contents = file_get_contents("/proc/net/wanrouter/status");
		if(!preg_match_all("/wanpipe/",$contents,$matches)) {
			return false;
		}
		return true;
	}

	/**
	 * Check if Sangoma Hardware Exists
	 * @return boolean True if hardware exists, false if not
	 */
	public function sangomaHardwareExists() {
		$process = new Process('lspci -n -d 1923:');
		try {
			$process->mustRun();
			$out = $process->getOutput();
			return !empty($out);
		} catch (ProcessFailedException $e) {
			return false;
		}
	}

	/**
	 * Write out default global.conf file if missing
	 * and if wanpipe*.conf do not exist
	 * @param  object $output The CLI Output object
	 */
	public function checkDefaultSangomaGlobal($output) {
		$files = glob("/etc/wanpipe/wanpipe*.conf");
		if(!empty($files)) {
			return;
		}
		$file = "/etc/wanpipe/global.conf";
		if(!file_exists($file)) {
			\FreePBX::Modules()->loadFunctionsInc('dahdiconfig');
			$dahdi_cards = new \dahdi_cards();
			if(isset($dahdi_cards->modules['sangoma']) && is_object($dahdi_cards->modules['sangoma'])) {
				$output->writeln("<info>"._("Writing out default Sangoma conf")."</info>");
				$dahdi_cards->modules['sangoma']->generateConf($file,true);
			}
		}
	}

	public function postStartFreepbx($output) {
		$this->setConfig("restarting",false);
	}

	/**
	 * Start FreePBX for fwconsole hook
	 * @param object $output The output object.
	 */
	public function startFreepbx($output) {
		$dahdiexec = $this->freepbx->Config->get("DAHDIEXEC");
		if(!file_exists($dahdiexec)) {
			$output->writeln("<error>"._("DAHDI NOT FOUND [Suggest Uninstalling the Dahdi Configuration Module]!")."</error>");
			return;
		}
		if(!is_executable($dahdiexec)) {
			$output->writeln("<error>".sprintf(_("Unable to execute dahdi: $s"), $dahdiexec)."</error>");
			return;
		}

		if($this->sangomaHardwareExists()) {
			//check for wanpipe*, if none then generate global if it doesnt exist
			$this->checkDefaultSangomaGlobal($output);
			if(!$this->wanrouterRunning()) {
				$wanrouterLocation = fpbx_which("wanrouter");
				$wanrouterLocation = trim($wanrouterLocation);
				$process = new Process($wanrouterLocation.' start');
				try {
					$output->writeln(_("Starting Wanrouter for Sangoma Cards"));
					$process->mustRun();
					$output->writeln(_("Wanrouter Started"));
				} catch (ProcessFailedException $e) {
					$output->writeln("<error>".sprintf(_("Wanrouter Failed: %s")."</error>",$e->getMessage()));
				}
			} else {
				$output->writeln("<comment>Wanrouter: Already started</comment>");
			}
		}else{
			$output->writeln("<comment>Wanrouter: No valid Sangoma Hardware found, if you have no Sangoma cards this is OK</comment>");
		}

		$process = new Process($dahdiexec.' status');
		$process->run();
		if($process->getExitCode() == 3) {
			$process = new Process($dahdiexec.' start');
			try {
				$output->writeln(_("Starting DAHDi for Digium Cards"));
				$process->mustRun();
				$output->writeln(_("DAHDi Started"));
			} catch (ProcessFailedException $e) {
				$output->writeln("<error>".sprintf(_("DAHDi Failed: %s")."</error>",$e->getMessage()));
			}
		} else {
			$output->writeln("<comment>DAHDi: Already started</comment>");
		}

		\FreePBX::Modules()->loadFunctionsInc('dahdiconfig');
		$dahdi_cards = new \dahdi_cards();
		$dahdi_cards->checkHardware();
	}

	/**
	 * Stop FreePBX for fwconsole hook
	 * @param object $output The output object.
	 */
	public function stopFreepbx($output) {
		$dahdiexec = $this->freepbx->Config->get("DAHDIEXEC");
		if(!file_exists($dahdiexec)) {
			$output->writeln("<error>"._("DAHDI NOT FOUND [Suggest Uninstalling the Dahdi Configuration Module]!")."</error>");
			return;
		}
		if(!is_executable($dahdiexec)) {
			$output->writeln("<error>".sprintf(_("Unable to execute dahdi: $s"), $dahdiexec)."</error>");
			return;
		}


		if($this->sangomaHardwareExists()) {
			$wanrouterLocation = fpbx_which("wanrouter");
			$wanrouterLocation = trim($wanrouterLocation);
			$process = new Process($wanrouterLocation.' stop');
			try {
				$output->writeln(_("Stopping Wanrouter for Sangoma Cards"));
				$process->mustRun();
				$output->writeln(_("Wanrouter Stopped"));
			} catch (ProcessFailedException $e) {
				$output->writeln(sprintf(_("Wanrouter Failed: %s"),$e->getMessage()));
			}
		} else {
			$output->writeln("<comment>Wanrouter: No valid Sangoma Hardware found, if you have no Sangoma cards this is OK</comment>");
		}


		$process = new Process($dahdiexec.' stop');
		try {
			$output->writeln(_("Stopping DAHDi for Digium Cards"));
			$process->mustRun();
			$output->writeln(_("DAHDi Stopped"));
		} catch (ProcessFailedException $e) {
			$output->writeln(sprintf(_("DAHDi Failed: %s"),$e->getMessage()));
		}
	}

	/**
	 * Chown hook for freepbx fwconsole
	 */
	public function chownFreepbx() {
		$files = array();
		/* This is disabled because of FREEPBX-10859 and FREEPBX-11012
		 * Basically let root (or dahdi driver) control the permissions for these
		$files[] = array('type' => 'file',
												'path' => '/dev/zap',
												'perms' => 0644);
		$files[] = array('type' => 'file',
												'path' => '/dev/dahdi',
												'perms' => 0644);
		*/
		$files[] = array('type' => 'rdir',
												'path' => '/etc/dahdi',
												'perms' => 0755);
		$files[] = array('type' => 'execdir',
												'path' => '/etc/wanpipe',
												'perms' => 0755);
		$files[] = array('type' => 'file',
												'path' => __DIR__."/hooks/restartdahdi",
												'perms' => 0755);
		$files[] = array('type' => 'file',
												'path' => $this->freepbx->Config->get('DAHDIMODULESLOC'),
												'perms' => 0755);
		$files[] = array('type' => 'file',
												'path' => $this->freepbx->Config->get('DAHDIMODPROBELOC'),
												'perms' => 0644);
		$files[] = array('type' => 'file',
												'path' => $this->freepbx->Config->get('DAHDISYSTEMLOC'),
												'perms' => 0644);

		return $files;
	}
	public function ajaxRequest($req, &$setting) {
		switch ($req) {
			case 'restart':
			case 'reload':
			case 'digitalspans':
			case 'analogspans':
			case 'checkrestart':
				return true;
			break;
			default:
				return false;
			break;
		}
	}
	public function ajaxHandler(){
		switch ($_REQUEST['command']) {
			case 'checkrestart':
				return array("started" => !$this->getConfig("restarting"));
			break;
			case 'restart':
				if(file_exists('/etc/incron.d/sysadmin')) {
					$this->setConfig("restarting",true);
					touch('/var/spool/asterisk/incron/dahdiconfig.restartdahdi');
					return array("status" => true);
				}
				return array("status" => false, "message" => _("This is not available on your system"));
			break;
			case 'reload':
				exec('asterisk -rx "module unload chan_dahdi.so"');
				exec('asterisk -rx "module load chan_dahdi.so"');
				return true;
			break;
			case 'digitalspans':
				\FreePBX::Modules()->loadFunctionsInc('dahdiconfig');
				$dahdi_cards = new \dahdi_cards();
				$spans = array();
				foreach($dahdi_cards->get_spans() as $key => $val){
					$spans[] = $val;
				}
				return $spans;
			break;
			case 'analogspans':
			break;
			default:
				return false;
			break;
		}
	}
}
