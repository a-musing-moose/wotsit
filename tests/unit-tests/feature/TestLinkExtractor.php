<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../../src/wotsit/Feature.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/LinkExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestLinkExtractor extends PHPUnit_Framework_TestCase
{

    const TEST_DOCUMENT = 'see <a href="moose.html">me</>';

    public function testExtraction()
    {
        $expected = array(
        new wotsit_Feature('moose.html', 1.2),
        );
        $extractor = new wotsit_feature_LinkExtractor();
        $features = $extractor->getFeatures(self::TEST_DOCUMENT);
        $this->assertEquals($expected, $features);
    }

    public function testExtractionWithCustomWeight()
    {
        $expected = array(
        new wotsit_Feature('moose.html', 25),
        );
        $extractor = new wotsit_feature_LinkExtractor();
        $extractor->setLinkWeight(25);
        $features = $extractor->getFeatures(self::TEST_DOCUMENT);
        $this->assertEquals($expected, $features);
    }

}