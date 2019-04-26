<?php
namespace ServerBackup;

use pocketmine\Thread;
use PharData;
use Phar;

class RestoreThread extends Thread {
	private $datafolder, $folder;
	const DS = DIRECTORY_SEPARATOR;
	public function __construct(string $datafolder, string $folder) {
		$this->datafolder = $datafolder;
		$this->folder = $folder;
		$this->start();
	}
	public function run() {
		$pre = '[' . date('H:i:s') . '] [Restore thread/INFO]: ';
		echo $pre . '[2/4] Decompressing gz archive' . PHP_EOL;
		$name = fread(fopen($this->datafolder . self::DS . 'backups' . self::DS . 'archiveName', 'r'), filesize($this->datafolder . self::DS . 'backups' . self::DS . 'archiveName'));
		$archive = new PharData($this->datafolder . self::DS . 'backups' . self::DS . $name);
		$name = str_replace('.gz', '', $name);
		@unlink($this->datafolder . self::DS . 'backups' . self::DS . $name);
		$archive->compress(Phar::NONE);
		echo $pre . '[3/4] Restoring' . PHP_EOL;
		unset($archive);
		$archive = new PharData($this->datafolder . self::DS . 'backups' . self::DS . $name);
		$archive->extractTo($this->folder, null, true);
		echo $pre . '[4/4] Clearing temporary archive';
		unset($archive);
		unlink($this->datafolder . self::DS . 'backups' . self::DS . $name);
		$conf = str_replace(dirname($name) . self::DS, '', $name . '.gz');
		fwrite($f = fopen($this->datafolder . self::DS . 'backups' . self::DS . 'archiveName', 'w+'), $conf);
		fclose($f);
	}
}