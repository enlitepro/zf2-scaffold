<?php

define('SCAFFOLD_ROOT', dirname(__DIR__));

/** @var Composer\Autoload\ClassLoader $autoload */
$autoload = include __DIR__ . "/../vendor/autoload.php";
$autoload->add('ScaffoldTest', __DIR__);