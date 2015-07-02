<?php

set_time_limit(0);

$_SERVER['FLOW_ROOTPATH'] = $_POST['FLOW_ROOTPATH'];
$context = $_POST['FLOW_CONTEXT'];
$time = intval($_POST['time']);
$hash = $_POST['hash'];

if (strlen($hash) < 32) {
	echo "ERROR: Hash not set";
	exit(1);
}

// ABSOLUTE path needed, we cannot trust FLOW_ROOTPATH yet.
$cliKeyPathAndFilename = __DIR__ . '/../../../../Configuration/CliKey.php';
if (!file_exists($cliKeyPathAndFilename)) {
	echo "ERROR: CLI Key not set";
	exit(1);
}

include($cliKeyPathAndFilename);

if (!isset($cliKey)) {
	echo "ERROR: CLI Key not loadable";
	exit(1);
}


$data = array(
	'FLOW_ROOTPATH' => $_SERVER['FLOW_ROOTPATH'],
	'FLOW_CONTEXT' => $context,
	'argv' => $_POST['argv'],
	'time' => $time
);
$hashInVerification = hash_hmac('sha1', json_encode($data), $cliKey);

if ($hashInVerification !== $hash) {
	echo "ERROR: Hash Verification Error";
	exit(1);
}

if ($time + 20 < time()) {
	echo "ERROR: Timestamp invalid";
	exit(1);
}

// At this point, we can trust the request parametes.

$_SERVER['argv'] = json_decode($_POST['argv']);

define('FLOW_OVERRIDDEN_PHP_SAPI', 'cli');

require(__DIR__ . '/../Classes/TYPO3/Flow/Core/Bootstrap.php');


$bootstrap = new \TYPO3\Flow\Core\Bootstrap($context);
$bootstrap->run();
