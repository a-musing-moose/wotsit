# Wotsit - A Bayesian Classification Library for PHP

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
This will run all tests, create documentation (in the /docs folder) and creates the Wotsit.phar file

## Usage

### Basic Usage

With a pre-trained classifier:
    require 'Wotsit.phar';
    //initialise storage object with previously learned data
    $storage = new wotsit_storage_Dbm('data.db');

    $extractor = new wotsit_feature_WordExtractor();
    $classfier = new wotsit_classifier_NaiveBayesian($extractor, $storage);

    //classify the passed in text
    $category = $classfier->classify($someText);

Training:
    require 'Wotsit.phar';
    $storage = new wotsit_storage_Dbm('new.db');

    $extractor = new wotsit_feature_WordExtractor(); //NB: You can also pass in a stemmer if you desire
    $classfier = new wotsit_classifier_NaiveBayesian($extractor, $storage);

    $goodData = array(); //should be examples of documents which are in the category 'good'
    $badData = array(); //should be examples of documents which are in the category 'bad'

    //Adding examples of 'good' data
    foreach ($goodData as $goodDatum) {
        $classfier->train($goodDatum, 'good');
    }

    //adding examples of 'bad' data
    foreach ($badData as $badDatum) {
        $classfier->train($badDatum, 'bad');
    }

For more informtaion see the API documentation which can be build by running:
    phing api-docs

## Running Tests

You can either run the tests by calling the appropriate targets with phing:
    phing unit-report
    phing integration-report
    phing bdd-report

The above commands will run the specified test suite and build a report within the docs folder

You can also run the unit tests from the phar file itself:
    php Wotsit.phar test

There are a couple of other things you can run directly from the phar file. Try:
    php Wotsit.phar help