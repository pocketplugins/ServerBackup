<?php
namespace ServerBackup;
Main();
function Main() {
	$pre = '[' . date('H:i:s' . '] [SR] ');
	if(php_sapi_name() != 'cli') {
		echo $pre . 'Run this from cli';
		exit(1);
	}
	$ans = readline($pre . 'This is SR(ServerRestorer) Do you want to continue? [y/N] ');
	if(strtolower($ans) = 'y') Restore();
	exit(1);
}
function Restore() {
	$pre = '[' . date('H:i:s' . '] [SR] ');
	$dir = realpath(dirname(__FILE__));
	$backups = $dir . DIRECTORY_SEPARATOR . 'backups' . DIRECTORY_SEPARATOR;
	echo $pre . 'Backup archive and archiveName files should be located at ' . $backups;
	$name = null;
	$file = null;
	try {
		$name = fread($f = fopen($backups . 'archiveName', 'r'), filesize($backups . 'archiveName'));
		fclose($f);
	} catch (\Exception $e) {
		echo $pre . $e->getMessage();
	}
	$file = file_exists($backups . $name) ? $backups . $name : null;
	if($file === null) {
		echo $pre . 'Archive not found try checking file address and its name at archiveName';
		sleep(2);
		exit(1);
	}
	$archive = new \PharData($file);
	$name = str_replace('.gz', '', $name);
	@unlink($filename)
}