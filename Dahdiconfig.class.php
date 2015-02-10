<?php
// vim: set ai ts=4 sw=4 ft=php:
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2014 Schmooze Com Inc.
//
namespace FreePBX\modules;
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
	}
}
