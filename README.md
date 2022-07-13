Symfony  Api Demo
==========
This is a Symfony test api

Symfony project 5.4
-------------------
  * **NOTE**
  This project is build using **docker** you need to install docker before installation process.
  ``` bash
  $ composer update
  ```
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
## For swagger 

``` bash
http://127.0.0.1:8082/api/doc
```

# Continuous Integration

* **Install Git Hook**
``` bash
$ bash dev-tools/install-hooks.sh
```
This will install an hook in your local .git `.git/hooks/pre-commit`. Each time you make a commit, the `ci-integration.sh` script will be executed.

# PHP Unit Test

* Run tests for the specific class
``` bash
$ ./vendor/bin/phpunit 
```
