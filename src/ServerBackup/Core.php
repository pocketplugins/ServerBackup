<?php
namespace ServerBackup;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;

class Core extends PluginBase {
	private $config, $compressor;
	public function onLoad() {
		if(!extension_loaded('zlib')) {
			$this->getServer()->getLogger()->debug("Zlib extension isn't loaded");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}
	public function package(CommandSender $sender) : void {
		$sender->sendMessage('[1/4] Starting thread');
		new CompressThread(realpath($this->getDataFolder()), realpath($this->getDataFolder() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'), $sender);
	}
	public function restore(CommandSender $sender) : void {
		$sender->sendMessage('[1/4] Starting thread');
		new RestoreThread(realpath($this->getDataFolder()), realpath($this->getDataFolder() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'), $sender);
		$this->getServer()->shutdown();
	}
	public function onEnable() {
		$this->reloadConfig();
		$this->getServer()->getCommandMap()->register('[ServerBackup]', new BackupCommand($this));
	}
}