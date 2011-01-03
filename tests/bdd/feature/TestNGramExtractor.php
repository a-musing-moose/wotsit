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
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/Stemmer.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iExtractFeatures.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/WordExtractor.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/NGramExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestNGramExtractor extends PHPUnit_Extensions_Story_TestCase
{

	const TEST_DOCUMENT = "jon";
	const TEST_DOCUMENT_FOR_STEMMING = "cats";
	
	public function testNGramsAreExtracted()
	{
		$expected = array(
			new wotsit_Feature('  j'),
			new wotsit_Feature(' jo'),
			new wotsit_Feature('jon'),
			new wotsit_Feature('on '),
			new wotsit_Feature('n  '),
		);
		$this->given('a new ngram extractor')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT, $expected);
	}

	public function testExtractionWithStemmerActuallyStemsWords()
	{
		$expected = array(
			new wotsit_Feature('  c'),
			new wotsit_Feature(' ca'),
			new wotsit_Feature('cat'),
			new wotsit_Feature('at '),
			new wotsit_Feature('t  '),
		);
		
		$this->given('a new ngram extractor with a stemmer')
        ->then('the features returned for the given document must be', self::TEST_DOCUMENT_FOR_STEMMING, $expected);
	}
	
	//BBD FUNCTIONS//
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new ngram extractor':
                $world['extractor'] = new wotsit_feature_NGramExtractor();
                break;
            case 'a new ngram extractor with a stemmer':
            	$world['extractor'] = new wotsit_feature_NGramExtractor(3, 2, 20, new wotsit_feature_Stemmer());
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