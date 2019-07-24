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
use Plugin\PHPExcel;
use Plugin\Curl AS Curl;

Http::mimeType("html", "UTF-8");

$urlInfo1 = array(
	'url' => 'www.word.com/web.php',
	'params' => array(
		'content' => 'header',
	),
);
$urlInfo2 = array(
	'url' => 'www.word.com/web.php',
	'params' => array(
		'header' => Curl::header('json'),
	),
);

$urlInfos = array($urlInfo1, $urlInfo2);
for($i=0;$i<100;$i++) {
	$urlInfos[] = $urlInfo1;
}


$result = Curl::postMulti($urlInfos);

print_r($result);die;

echo "Lonny";