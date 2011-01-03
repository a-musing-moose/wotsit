<?php
require_once dirname(__FILE__).'/TestNaiveBayesian.php';
require_once dirname(__FILE__).'/TestFisher.php';
require_once dirname(__FILE__).'/storage/TestMongoDb.php';

class IntegrationTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Wotsit tests');
        $suite->addTestSuite('TestNaiveBayesian');
        $suite->addTestSuite('TestFisher');
        $suite->addTestSuite('storage_TestMongoDb');
        return $suite;
    }
}
