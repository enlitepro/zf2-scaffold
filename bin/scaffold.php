#!/usr/bin/php

<?php

use Scaffold\Console\EntityCommand;
use Symfony\Component\Console\Application;

require __DIR__ . "/../vendor/autoload.php";

$application = new Application();
$application->add(new EntityCommand());

$application->run();