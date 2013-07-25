#!/usr/bin/php

<?php

define('SCAFFOLD_ROOT', dirname(__DIR__));

use Scaffold\Console;
use Symfony\Component\Console\Application;

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require __DIR__ . "/../vendor/autoload.php";
} elseif (file_exists(__DIR__ . "/../../../autoload.php")) {
    require __DIR__ . "/../../../autoload.php";
} else {
    throw new \RuntimeException('Where autoload, buddy?');
}

$application = new Application();
$application->add(new Console\EntityCommand());
$application->add(new Console\ServiceCommand());
$application->add(new Console\ExceptionCommand());
$application->add(new Console\ModuleCommand());

$application->run();