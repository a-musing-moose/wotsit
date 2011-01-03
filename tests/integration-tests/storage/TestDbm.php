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
class storage_TestDbm extends PHPUnit_Framework_TestCase
{

    /**
     * @var wotsit_Classifier
     */
    private $classifier;

    /**
     * @var string
     */
    private $filename;

    public function setup()
    {
        if (!extension_loaded('dba')) {
            $this->markTestSkipped("DBA extension not available");
        }
        $this->filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'test.dbm';
        $storage = new wotsit_storage_Dbm($this->filename);
        $extractor = new wotsit_feature_WordExtractor();
        $this->classifier = new wotsit_classifier_NaiveBayesian($extractor, $storage);
        $this->train();
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
        unset($this->classifier);
        @unlink($this->filename);
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