# Wotsit - A Bayesian Filtering Library for PHP

## Installation

### Install PHPUnit
    sudo pear channel-discover pear.phpunit.de
    sudo pear install phpunit/PHPUnit

### Install Phing
    sudo pear channel-discover pear.phing.info
    sudo pear install phing/phing


### Building
Wotsit is designed to be packaged as a phar file. To create the package run:
    phing build-all
This will build run all tests, create documentation (in the /docs folder) and creates the Wotsit.phar file

## Usage
See documentation which can be build by running:
    phing api-docs
