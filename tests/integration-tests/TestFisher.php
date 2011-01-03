<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../Wotsit.phar';

/**
 * @package wotsit
 */
class TestFisher extends PHPUnit_Framework_TestCase
{

    /**
     * @var wotsit_Classifier
     */
    private $classifier;

    public function setup()
    {
        $extractor = new wotsit_feature_WordExtractor();
        $storage = new wotsit_storage_Memory();
        $this->classifier = new wotsit_classifier_Fisher($extractor, $storage);
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
        $classfication = $this->classifier->classify('make quick money at the online casino');
        $this->assertEquals($expected, $classfication);
    }

    public function testGettingClassifications()
    {
        $classifications = $this->classifier->classifications('no body owns the water');
        $this->assertArrayHasKey('good', $classifications);
        $this->assertArrayHasKey('bad', $classifications);
    }

    public function teardown()
    {
        $this->classifier = null;
    }

    private function train()
    {
        $sampleData = fopen(dirname(__FILE__) . '/fixtures/sampleData.txt', 'r');
        while (($data = fgetcsv($sampleData)) !== FALSE) {
            $this->classifier->train($data[0], $data[1]);
        }
        fclose($sampleData);
    }


}