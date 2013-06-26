#!/usr/bin/php

<?php

use Scaffold\Console\EntityCommand;
use Symfony\Component\Console\Application;

if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require __DIR__ . "/../vendor/autoload.php";
} elseif (file_exists(__DIR__ . "/../autoload.php")) {
    require __DIR__ . "/../autoload.php";
} else {
    throw new \RuntimeException('Where autoload, buddy?');
}

$application = new Application();
$application->add(new EntityCommand());

$application->run();