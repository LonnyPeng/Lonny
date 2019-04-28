<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

$dir = __DIR__ . "/Plugin/";
$handle = opendir($dir);
while ($file = readdir($handle)) {
	if (in_array($file, array(".", "..")) || is_dir($dir . $file)) {
		continue;
	}

	require_once($dir . $file);
}

use Plugin\Http;
use Plugin\Func;
use Plugin\Translate;
use Plugin\RSA AS RSA;
use PHPExcel;

Http::mimeType("html", "UTF-8");

print_r(Func::bankCardInfo('6222600260001072444'));die;

echo "Lonny";