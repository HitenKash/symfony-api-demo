Symfony  Api Demo
==========
This is a Symfony test api

Symfony project 5.4
-------------------
  * **NOTE**
  This project is build using **docker** you need to install docker before installation process.

# Installation

* Install external packaged
``` bash
$ git clone https://github.com/kailashkds/symfony-api-demo.git
```
``` bash
$ cd symfony-api-demo
```
``` bash
$ docker-compose up -d
```
``` bash
Add 127.0.0.1 dev.api.local in you host file
```
## For swagger 
In our case the post is 8082
``` bash
http://dev.api.local:{{ port defined in env}}/api/doc
```

# Continuous Integration

* **Install Git Hook**
``` bash
$ bash dev-tools/install-hooks.sh
```
This will install an hook in your local .git `.git/hooks/pre-commit`. Each time you make a commit, the `ci-integration.sh` script will be executed.

# PHP Unit Test
* You need to login inside docker to run php unit tests
``` bash
$ docker exec -it api-php-container bash
```
* Run tests for the specific class
``` bash
$ php bin/phpunit
```