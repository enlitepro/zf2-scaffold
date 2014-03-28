Scaffold for Zend Framework 2 [![Build Status](https://travis-ci.org/enlitepro/zf2-scaffold.png?branch=master)](https://travis-ci.org/enlitepro/zf2-scaffold)[![Coverage Status](https://coveralls.io/repos/enlitepro/zf2-scaffold/badge.png?branch=master)](https://coveralls.io/r/enlitepro/zf2-scaffold?branch=master)
=============================


INSTALL
=======

The recommended way to install is through composer.

```json
{
    "require-dev": {
        "enlitepro/zf2-scaffold": "~1.0.0"
    }
}
```

USAGE
=====

```
./vendor/bin/scaffold

Available commands:
  controller   Generate controller
  entity       Generate entity
  exception    Generate exceptions (RuntimeException, NotFoundException and other)
  form         Generate form factory and write to service.config.php
  full         Generate all available (without module skeleton)
  module       Generate module skeleton
  options      Generate options, options DI trait, options factory and write to service.config.php
  repository   Generate repository and repository DI trait
  service      Generate service, service DI trait, service factory, service test and write to service.config.php


./vendor/bin/scaffold service [--no-service] [--no-trait] [--no-factory]
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


./vendor/bin/scaffold options [--no-options] [--no-trait] [--no-factory]
    [--only-options] [--only-trait] [--only-factory] <module> <name>
 <module>              Name of module
 <name>                Options name (will be append Options postfix)
 --no-options          Disable options generation
 --no-trait            Disable trait generation
 --no-factory          Disable factory generation
 --only-options        Generate only options
 --only-trait          Generate only trait
 --only-factory        Generate only factory
```

Creating bare module
====================

If you want create separate module, you can use `--bare` option, which create following folder struct

```
config/
src/
    ModuleName/
        Module.php
test/
phpunit.xml
```