<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
include_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/Feature.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/Stemmer.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/WordExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestWordExtractor extends PHPUnit_Extensions_Story_TestCase
{

    const TEST_DOCUMENT = "the quick brown";
    const TEST_DOCUMENT_FOR_STRIPPING = 'see <a href="moose.html">me</>';
    const TEST_DOCUMENT_FOR_STEMMING = "swimming cats";

    public function testWordsAreExtracted()
    {
        $expected = array(
            new wotsit_Feature('the'),
            new wotsit_Feature('quick'),
            new wotsit_Feature('brown'),
        );
        
        $this->given('a new word extractor')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT, $expected);

    }

    public function testHtmlTagsAreStrippedOut()
    {
        $expected = array(
            new wotsit_Feature('see'),
            new wotsit_Feature('me'),
        );
        
        $this->given('a new word extractor')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT_FOR_STRIPPING, $expected);
        
    }
    
    public function testExtractionWithStemmerActuallyStemsWords()
    {
    	$expected = array(
            new wotsit_Feature('swim'),
            new wotsit_Feature('cat'),
        );
        
        $this->given('a new word extractor with a stemmer')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT_FOR_STEMMING, $expected);
    }
    
    //BDD FUNCTIONS//
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new word extractor':
                $world['extractor'] = new wotsit_feature_WordExtractor();
                break;
            case 'a new word extractor with a stemmer':
            	$world['extractor'] = new wotsit_feature_WordExtractor(2, 20, new wotsit_feature_Stemmer());
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