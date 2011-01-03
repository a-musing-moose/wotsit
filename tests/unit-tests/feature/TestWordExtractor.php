<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../../src/wotsit/Feature.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/Stemmer.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/WordExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestWordExtractor extends PHPUnit_Framework_TestCase
{

    const TEST_DOCUMENT = "the quick brown";
    const TEST_DOCUMENT_FOR_STRIPPING = 'see <a href="moose.html">me</>';
    const TEST_DOCUMENT_FOR_STEMMING = "swimming cats";

    public function testExtraction()
    {
        $expected = array(
            new wotsit_Feature('the'),
            new wotsit_Feature('quick'),
            new wotsit_Feature('brown'),
        );
        $extractor = new wotsit_feature_WordExtractor();
        $features = $extractor->getFeatures(self::TEST_DOCUMENT);
        $this->assertEquals($expected, $features);
    }

    public function testStripping()
    {
        $expected = array(
            new wotsit_Feature('see'),
            new wotsit_Feature('me'),
        );
        $extractor = new wotsit_feature_WordExtractor();
        $features = $extractor->getFeatures(self::TEST_DOCUMENT_FOR_STRIPPING);
        $this->assertEquals($expected, $features);
    }
    
    public function testExtractionWithStemmer()
    {
    	$expected = array(
            new wotsit_Feature('swim'),
            new wotsit_Feature('cat'),
        );
        $extractor = new wotsit_feature_WordExtractor(2, 20, new wotsit_feature_Stemmer());
        $features = $extractor->getFeatures(self::TEST_DOCUMENT_FOR_STEMMING);
        $this->assertEquals($expected, $features);
    }

}