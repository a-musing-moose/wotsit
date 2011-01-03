<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/Feature.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/LinkExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestLinkExtractor extends PHPUnit_Extensions_Story_TestCase
{

    const TEST_DOCUMENT = 'see <a href="moose.html">me</>';

    public function testLinksAreExtracted()
    {
        $expected = array(
        	new wotsit_Feature('moose.html', 1.2),
        );
        
        $this->given('a new link extractor')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT, $expected);
        
       
    }
    
    public function testExtractionWithCustomLinkWeightSetCreatesFeaturesWithThatWeight()
    {
        $expected = array(
        	new wotsit_Feature('moose.html', 25),
        );
        
        $this->given('a new link extractor')
        ->when('a custom link weight is set', 25)
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT, $expected);
    } 

    //BBD FUNCTIONS //
    
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new link extractor':
                $world['extractor'] = new wotsit_feature_LinkExtractor();
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
    		case 'a custom link weight is set':
    			$world['extractor']->setLinkWeight($arguments[0]);
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
            case 'the features returned for the given document must be':
                $features = $world['extractor']->getFeatures($arguments[0]);
                $this->assertEquals($arguments[1], $features);
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }
}