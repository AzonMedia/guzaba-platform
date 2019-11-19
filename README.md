# Guzaba Platform

## Introduction

Guzaba Platform is a front end and plugin management system for [Guzaba2 Framework](https://github.com/AzonMedia/guzaba2).

## Deployment in containers

There is a [docker-compose file](https://github.com/AzonMedia/guzaba-platform/blob/master/app/dockerfiles/GuzabaPlatformDev/docker-compose.yml) with preset environment variables available at [./app/dockerfiles/GuzabaPlatformDev/](https://github.com/AzonMedia/guzaba-platform/tree/master/app/dockerfiles/GuzabaPlatformDev). 

Before the application is started it needs to be deployed on the host system with:

```
# clone the repository
$ git clone git@github.com:AzonMedia/guzaba-platform.git
```

To deploy the application in containers execute:
```
$ ./app/bin/start_containers
```
This will start the following containers:
- swoole (in interactive mode) on port 8081
- redis on port 6379 (exported for debug purpose)
- mysql on port 3306 (exported for debug purpose)
- phpmyadmin on port 8085
- phpredisadmin on port 8086

The login for phpmyadmin is "root" : "somerootpass".

The login for phpredisadmin is "admin" : "admin".

**NOTE - on first run:** On the first start of the application the database needs to be imported in MySQL. This can be done through phpmyadmin or directly over the exposed port 3306.
The database dump is available at [./app/database/guzaba2.sql](https://github.com/AzonMedia/guzaba-platform/blob/master/app/database/guzaba2.sql).

After the containers are started there will be no application running yet on port 8081. This needs to be started manually. To get into the container:
```
$ docker exec -it guzabaplatformdev_swoole_1 /bin/bash
```
If the above command produces an error this is most probably related to the container name. It may differ. To find the correct name list all the running containers with:
```
$ docker ps
```  

**NOTE - on first run:** The dependencies need to be installed - inside the container execute:
```
$ cd /home/local/app
$ composer install
```
**NOTE - on first run:** The front end needs to be compiled - inside the container execute:
```
$ /home/local/app/public_src/build_prod
```

There is no need to set up local configuration in the ./app/registry because the [environment file](https://github.com/AzonMedia/guzaba-platform/blob/master/app/dockerfiles/GuzabaPlatformDev/guzaba-platform.env) contains all the needed variables.

To start the application inside the container do:
```
$ /home/local/app/bin/start_server
```

## Manual install

```
# clone the repository
$ git clone git@github.com:AzonMedia/guzaba-platform.git

# Fetch dependencies
$ composer install

# Create local configuration
$ cp app/registry/local.php.dist app/registry/local.php

# Build the front end
$ app/public_src/build_prod
```

Change the settings in your ```app/registry/local.php``` so you can connect to the MySQL and to the Redis server. 

## Documentation

The complete documentation is available at [Guzaba Platform Documentation](https://github.com/AzonMedia/guzaba-platform-docs).




## Directory structure
- [app](./app) - the Guzaba Platform application
- [vendor](./vendor) - Composer dependencies


