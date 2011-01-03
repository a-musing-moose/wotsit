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
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/ExtractorCollection.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestExtractorCollection extends PHPUnit_Framework_TestCase
{

    public function testExtraction()
    {
        $expected = array(
            new wotsit_Feature('test'),
            new wotsit_Feature('test'),
        );
        $extractor = new wotsit_feature_ExtractorCollection();
        $extractor->addExtractor($this->getMockExtractor())
                  ->addExtractor($this->getMockExtractor());
        $features = $extractor->getFeatures('');
        $this->assertEquals($expected, $features);
    }

    private function getMockExtractor()
    {
        $return = array(new wotsit_Feature('test'));
        $extractor = $this->getMock('wotsit_iExtractFeatures', array('getFeatures'));
        $extractor->expects($this->once())
        ->method('getFeatures')
        ->with($this->equalTo(''))
        ->will($this->returnValue($return));
        return $extractor;
    }

}