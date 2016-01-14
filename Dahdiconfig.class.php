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

		if(file_exists("/etc/wanpipe/wanrouter.rc")) {
			//TODO: need to use loadConfig when it is able to understand # as comments.
			//Before PHP 7
			$wanrouterconf = @parse_ini_file("/etc/wanpipe/wanrouter.rc");
			if(!empty($wanrouterconf['WAN_DEVICES'])) {
				$wanrouterconf['WAN_DEVICES'] = trim($wanrouterconf['WAN_DEVICES']);
				$wandevices = explode(" ", $wanrouterconf['WAN_DEVICES']);
				$confspresent = 0;
				foreach ($wandevices as $wandev) {
					if(file_exists('/etc/wanpipe/'.trim($wandev).'.conf')){
						$confspresent++;
					}
				}

				$process = new Process('which wanrouter');
				$process->run();
				if ($process->isSuccessful()) {
					$wanrouterLocation = $process->getOutput();
					if(!empty($wanrouterLocation) && $confspresent > 0) {
						$wanrouterLocation = trim($wanrouterLocation);
						$process = new Process($wanrouterLocation.' status');
						$process->run();
						if($process->isSuccessful()) {
							$out = $process->getOutput();
							if(preg_match('/Router is stopped/i',$out)) {
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
						} else {
							$output->writeln("<error>Wanrouter: Unexpected response</error>");
						}
					}else{
						if(empty($wanrouterLocation)){
							$output->writeln("<error>Couldn't find the Wanrouter executable</error>");
						}
						if($confspresent == 0){
							$output->writeln("<comment>Wanrouter: No valid device configs found, if you have no Sangoma cards this is OK</comment>");
						}
					}
				}
			} else {
				$output->writeln("<comment>Wanrouter: No valid device configs found, if you have no Sangoma cards this is OK</comment>");
			}
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

		if(file_exists("/etc/wanpipe/wanrouter.rc")) {
			//TODO: need to use loadConfig when it is able to understand # as comments.
			//Before PHP 7
			$wanrouterconf = @parse_ini_file("/etc/wanpipe/wanrouter.rc");
			if(!empty($wanrouterconf['WAN_DEVICES'])) {
				$wanrouterconf['WAN_DEVICES'] = trim($wanrouterconf['WAN_DEVICES']);
				$wandevices = explode(" ", $wanrouterconf['WAN_DEVICES']);
				$confspresent = 0;
				foreach ($wandevices as $wandev) {
					if(file_exists('/etc/wanpipe/'.trim($wandev).'.conf')){
						$confspresent++;
					}
				}
				if(!empty($confspresent)) {
					$process = new Process('which wanrouter');
					$process->run();

					// executes after the command finishes
					if ($process->isSuccessful()) {
						$wanrouterLocation = $process->getOutput();
						if(!empty($wanrouterLocation)) {
							$wanrouterLocation = trim($wanrouterLocation);
							$process = new Process($wanrouterLocation.' stop');
							try {
								$output->writeln(_("Stopping Wanrouter for Sangoma Cards"));
								$process->mustRun();
								$output->writeln(_("Wanrouter Stopped"));
							} catch (ProcessFailedException $e) {
								$output->writeln(sprintf(_("Wanrouter Failed: %s"),$e->getMessage()));
							}
						}
					}
				} else {
					$output->writeln("<comment>Wanrouter: No valid device configs found, if you have no Sangoma cards this is OK</comment>");
				}
			}
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
}
