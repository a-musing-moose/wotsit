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
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/NGramExtractor.php';

/**
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_TestNGramExtractor extends PHPUnit_Framework_TestCase
{

	const TEST_DOCUMENT = "jon";
	const TEST_DOCUMENT_FOR_STEMMING = "cats";
	
	public function testExtraction()
	{
		$expected = array(
			new wotsit_Feature('  j'),
			new wotsit_Feature(' jo'),
			new wotsit_Feature('jon'),
			new wotsit_Feature('on '),
			new wotsit_Feature('n  '),
		);
		$extractor = new wotsit_feature_NGramExtractor();
		$features = $extractor->getFeatures(self::TEST_DOCUMENT);
		$this->assertEquals($expected, $features);
	}

	public function testExtractionWithStemmer()
	{
		$expected = array(
			new wotsit_Feature('  c'),
			new wotsit_Feature(' ca'),
			new wotsit_Feature('cat'),
			new wotsit_Feature('at '),
			new wotsit_Feature('t  '),
		);
		$extractor = new wotsit_feature_NGramExtractor(3, 2, 20, new wotsit_feature_Stemmer());
		$features = $extractor->getFeatures(self::TEST_DOCUMENT_FOR_STEMMING);
		$this->assertEquals($expected, $features);
	}
}