<?php
namespace ServerBackup;

use pocketmine\plugin\PluginBase;

class Core extends PluginBase {
	private $config;
	public function onEnable() {
		$this->reloadConfig();
	}
}