<?php
namespace ServerBackup;

use pocketmine\Thread;
use PharData;

class CompressThread extends Thread {
	private $datafolder, $folder;
	const DS = DIRECTORY_SEPARATOR;
	public function __construct(string $datafolder, string $folder) {
		$this->datafolder = $datafolder;
		$this->folder = $folder;
		$this->start();
	}
	public function run() {
		$pre = '[' . date('H:i:s') . '] [Compress thread/INFO]: ';
		echo $pre . '[2/4] Creating temporary tar archive' . PHP_EOL;
		$archive = new PharData($name = $this->datafolder . self::DS . 'backups' . self::DS . date('Y-m-d-HisT') . '.tar');
		$this->chmod($this->folder, 0775);
		$archive->buildFromDirectory($this->folder);
		echo $pre . '[3/4] Creating gz archive and configuration' . PHP_EOL;
		$archive->compress(4096);
		@unlink($this->datafolder . self::DS . 'backups' . self::DS . 'archiveName');
		$conf = str_replace(dirname($name) . self::DS, '', $name . '.gz');
		fwrite($f = fopen($this->datafolder . self::DS . 'backups' . self::DS . 'archiveName', 'w+'), $conf);
		fclose($f);
		echo $pre . '[4/4] Removing temporary tar archive' . PHP_EOL;
		unset($archive);
		unlink($name);
		echo $pre . 'Backed up!' . PHP_EOL;
	}
	public function chmod(string $path, $mode) : bool {
		if(!is_dir($path)){
			return chmod($path, $mode);
		}
		$dir = opendir($path);
		while($file = readdir($dir)) {
			if($file != '.' && $file != '..'){
				$fullpath = $path . self::DS . $file;
				if(!is_dir($fullpath)){
					if(!chmod($fullpath, $mode)) return false;
				}else{
					if(!$this->chmod($fullpath, $mode)) return false;
				}
			}
		}
		closedir($dir);
		return chmod($path, $mode) ? true : false;
	}
}