Scaffold for Zend Framework 2 [![Build Status](https://travis-ci.org/enlitepro/zf2-scaffold.png)](https://travis-ci.org/enlitepro/zf2-scaffold)
=============================


INSTALL
=======

The recommended way to install is through composer.

```json
{
    "require": {
        "enlitepro/zf2-scaffold": "1.*"
    }
}
```

USAGE
=====

```
./vendor/bin/scaffold.php

Available commands:
  controller   Generate controller
  entity       Generate entity
  exception    Generate exceptions (RuntimeException, NotFoundException and other)
  form         Generate form factory and write to service.config.php
  full         Generate all available (without module skeleton)
  module       Generate module skeleton
  repository   Generate repository and repository DI trait
  service      Generate service, service DI trait, service factory, service test and write to service.config.php


./vendor/bin/scaffold.php service [--no-service] [--no-trait] [--no-factory]
    [--no-test] [--only-service] [--only-trait]
    [--only-factory] [--only-test] <module> <name>
 <module>              Name of module
 <name>                Name of service
 --no-service          Disable service generation
 --no-trait            Disable service trait generation
 --no-factory          Disable service factory generation
 --no-test             Disable service test generation
 --only-service        Generate only service
 --only-trait          Generate only trait
 --only-factory        Generate only factory
 --only-test           Generate only test

```