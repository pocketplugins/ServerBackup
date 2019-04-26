<?php
namespace ServerBackup;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

class BackupCommand extends PluginCommand {
	private $core;
	public function __construct(Core $owner) {
		parent::__construct('serverbackup', $owner);
		$this->core = $owner;
		$this->setUsage('Usage: /serverbackup <sub-cmd | help> [arguments]');
		$this->setDescription('ServerBackupManager');
		$this->setPermission('server.backup.cmd');
		$this->setAliases(array('sb'));
	}
	public function getCore() : Core {
		return $this->core;
	}
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$sender instanceof ConsoleCommandSender) {
			$sender->sendMessage('Use in console!');
		}
		if(!isset($args[0])) {
			$sender->sendMessage($this->getUsage());
			return;
		}
		switch($args[0]) {
			case 'backup':
				$this->core->package($sender);
				break;
			case 'restore':
				$this->core->restore($sender);
				break;
			default:
				$sender->sendMessage($this->getUsage());
		}
	}
}