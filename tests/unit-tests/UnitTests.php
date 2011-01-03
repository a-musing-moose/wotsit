<?php
require_once dirname(__FILE__).'/feature/TestExtractorCollection.php';
require_once dirname(__FILE__).'/feature/TestLinkExtractor.php';
require_once dirname(__FILE__).'/feature/TestNGramExtractor.php';
require_once dirname(__FILE__).'/feature/TestStemmer.php';
require_once dirname(__FILE__).'/feature/TestWordExtractor.php';
require_once dirname(__FILE__).'/storage/TestPersistentMemory.php';
require_once dirname(__FILE__).'/storage/TestMemory.php';
require_once dirname(__FILE__).'/storage/TestMongoDb.php';
require_once dirname(__FILE__).'/storage/TestSqlite.php';
require_once dirname(__FILE__).'/storage/TestDbm.php';
require_once dirname(__FILE__).'/TestFeature.php';

class UnitTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Wotsit tests');
        $suite->addTestSuite('wotsit_feature_TestExtractorCollection');
        $suite->addTestSuite('wotsit_feature_TestLinkExtractor');
        $suite->addTestSuite('wotsit_feature_TestNGramExtractor');
        $suite->addTestSuite('wotsit_feature_TestStemmer');
        $suite->addTestSuite('wotsit_feature_TestWordExtractor');
        $suite->addTestSuite('wotsit_storage_TestPersistentMemory');
        $suite->addTestSuite('wotsit_storage_TestMemory');
        $suite->addTestSuite('wotsit_storage_TestMongoDb');
        $suite->addTestSuite('wotsit_storage_TestSqlite');
        $suite->addTestSuite('wotsit_storage_TestDbm');
        $suite->addTestSuite('wotsit_TestFeature');
        return $suite;
    }
}
