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

		$process = new Process('which wanrouter');
		$process->run();

		// executes after the command finishes
		if ($process->isSuccessful()) {
			$wanrouterLocation = $process->getOutput();
			if(!empty($wanrouterLocation)) {
				$wanrouterLocation = trim($wanrouterLocation);
				$process = new Process($wanrouterLocation.' start');
				try {
					$output->writeln(_("Starting Wanrouter for Sangoma Cards"));
					$process->mustRun();
					$output->writeln(_("Wanrouter Started"));
				} catch (ProcessFailedException $e) {
					$output->writeln("<error>".sprintf(_("Wanrouter Failed: %s")."</error>",$e->getMessage()));
				}
			}
		}

		$process = new Process($dahdiexec.' start');
		try {
			$output->writeln(_("Starting DAHDi for Digium Cards"));
			$process->mustRun();
			$output->writeln(_("DAHDi Started"));
		} catch (ProcessFailedException $e) {
			$output->writeln("<error>".sprintf(_("DAHDi Failed: %s")."</error>",$e->getMessage()));
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

		$files[] = array('type' => 'file',
												'path' => '/dev/zap',
												'perms' => 0644);
		$files[] = array('type' => 'file',
												'path' => '/dev/dahdi',
												'perms' => 0644);
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
