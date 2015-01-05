<?php

use Nette\CommandLine\Parser;

require is_file(__DIR__ . '/../vendor/autoload.php')
	? __DIR__ . '/../vendor/autoload.php'
	: __DIR__ . '/../../../autoload.php';


set_exception_handler(function($e) {
	echo "ERROR: {$e->getMessage()}\n";
	exit(1);
});


$cmd = new Parser(<<<XX
Usage:
    php composer-symlinker.php <packages_file> [<project_dir>]

XX
, [
	'packages_file' => [Parser::REALPATH => TRUE],
	'project_dir' => [Parser::VALUE => getcwd(), Parser::REALPATH => TRUE],
]);

if ($cmd->isEmpty()) {
	$cmd->help();
	exit;
}

$options = $cmd->parse();

$symlinker = new ComposerSymlinker;
foreach (file($options['packages_file'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $dir) {
	echo "using $dir\n";
	$symlinker->addPackage($dir);
}

echo "appling to $options[project_dir]\n";
$symlinker->apply($options['project_dir']);
