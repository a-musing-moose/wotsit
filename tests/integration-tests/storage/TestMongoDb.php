<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../../Wotsit.phar';

/**
 * @package wotsit
 * @subpackage storage
 */
class storage_TestMongoDb extends PHPUnit_Framework_TestCase
{

    /**
     * @var wotsit_Classifier
     */
    private $classifier;

    /**
     * @var MongoDB
     */
    private $db;

    public function setup()
    {
        $m = new Mongo("mongodb://localhost", array("connect" => false));
        try {
            $m->connect();
            $this->db = $m->selectDB('wotsit'); // get a database object
            $storage = new wotsit_storage_MongoDb($this->db);
            $extractor = new wotsit_feature_WordExtractor();
            $this->classifier = new wotsit_classifier_NaiveBayesian($extractor, $storage);
            $this->train();
        } catch (MongoConnectionException $e) {
            $this->markTestSkipped('Unable to connect to MongoDB');
        }
    }


    public function testClassificationAsGood()
    {
        $expected = 'good';
        $classfication = $this->classifier->classify('no body owns the water');
        $this->assertEquals($expected, $classfication);
    }

    public function testClassificationAsBad()
    {
        $expected = 'bad';
        $classfication = $this->classifier->classify('money');
        $this->assertEquals($expected, $classfication);
    }

    public function teardown()
    {
        if (null != $this->db) {
            $this->db->drop();
        }
    }

    private function train()
    {
        $sampleData = fopen(dirname(__FILE__) . '/../fixtures/sampleData.txt', 'r');
        while (($data = fgetcsv($sampleData)) !== FALSE) {
            $this->classifier->train($data[0], $data[1]);
        }
        fclose($sampleData);
    }


}