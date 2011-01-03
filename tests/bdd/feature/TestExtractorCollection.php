<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
include_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/ExtractorCollection.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestExtractorCollection extends PHPUnit_Extensions_Story_TestCase
{

    public function testANewExtractorCollectionCallsAnAddedExtractor()
    {
        $features = array($this->getMockedFeature('test'));
    	$this->given('a new extractor collection')
    		 ->when('an extractor is added', $this->getMockExtractor($features))
    		 ->then('the features returned must be', $features);
    }

    private function getMockExtractor(array $features)
    {
        $return = array($this->getMockedFeature('test'));
        $extractor = $this->getMock('wotsit_iExtractFeatures', array('getFeatures'));
        $extractor->expects($this->once())
        ->method('getFeatures')
        ->with($this->equalTo(''))
        ->will($this->returnValue($return));
        return $extractor;
    }
    
 
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new extractor collection':
                $world['collection'] = new wotsit_feature_ExtractorCollection();
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }

    public function runWhen(&$world, $action, $arguments)
    {
    	$action = strtolower($action);
    	switch ($action) {
    		case 'an extractor is added':
    			$world['collection']->addExtractor($arguments[0]);
    			break;
    		default:
    			$this->notImplemented($action);
    			break;
    	}
    }

    public function runThen(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'the features returned must be':
                $features = $world['collection']->getFeatures('');
                $this->assertEquals($arguments[0], $features);
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }
    
    private function getMockedFeature($value)
    {
    	$mockFeature = $this->getMock('wotsit_Feature', array('getValue'), array(), '', false);
    	$mockFeature->expects($this->any())
             		->method('getValue')
             		->will($this->returnValue($value));
        return $mockFeature;
    }

}