<?php
// vim: set ai ts=4 sw=4 ft=php:
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2014 Schmooze Com Inc.
//
namespace FreePBX\modules;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class Dahdiconfig implements \BMO {
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

	public function sangomaHardwareExists() {
		$wanrouterLocation = fpbx_which("wanrouter");
		$process = new Process($wanrouterLocation.' hwprobe dump');
		try {
			$process->mustRun();
			$out = trim($process->getOutput());
			$lines = explode("\n",$out);
			$cardline = end($lines);
			$cards = explode("|",ltrim($cardline,"|"));
			if($cards[0] == "Card Cnt") {
				unset($cards[0]);
				foreach($cards as $card) {
					$parts = explode("=",$card);
					if($parts[1] == 1) {
						return true;
					}
				}
			}

		} catch (ProcessFailedException $e) {
			return false;
		}
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

		$wanrouterLocation = fpbx_which("wanrouter");
		if($this->sangomaHardwareExists()) {
			if(!$this->wanrouterRunning()) {
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

		$wanrouterLocation = fpbx_which("wanrouter");
		if($this->sangomaHardwareExists()) {
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
			case 'digitalspans':
			case 'analogspans':
				return true;
			break;
			default:
				return false;
			break;
		}
	}
	public function ajaxHandler(){
		\FreePBX::Modules()->loadFunctionsInc('dahdiconfig');
		$dahdi_cards = new \dahdi_cards();
		switch ($_REQUEST['command']) {
			case 'digitalspans':
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
