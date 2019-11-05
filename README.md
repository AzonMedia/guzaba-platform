# Guzaba Platform

## Introduction

Guzaba Platform is a front end and plugin management system for [Guzaba2 Framework](https://github.com/AzonMedia/guzaba2).

## Install

```
# Fetch dependencies
$ composer install

# Create local configuration
$ cp app/registry/local.php.dist app/registry/local.php
```

Change the settings in your ```app/registry/local.php``` so you can connect to the MySQL and to the Redis server. 

## Documentation

The complete documentation is available at [Guzaba Platform Documentation](https://github.com/AzonMedia/guzaba-platform-docs).

## Directory structure
- [app](./app) - the Guzaba Platform application
- [vendor](./vendor) - Composer dependencies