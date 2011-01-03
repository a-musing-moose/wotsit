<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/Stemmer.php';

/**
 * @package wotsit
 */
class wotsit_feature_TestStemmer extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @param string $word
	 * @param string $expected
	 */
	public function testStemWord()
	{
		$stemmer = new wotsit_feature_Stemmer();
		$expected = array();
		$output = array();
		foreach ($this->getWordList() as $value) {
			$expected[] = $value[1];
			$output[] = $stemmer->stemWord($value[0]);
		}
		$this->assertEquals($expected, $output);
	}
	
	public function testStemWords()
	{
		$stemmer = new wotsit_feature_Stemmer();
		$inputs = array();
		$expected = array();
		foreach ($this->getWordList() as $value) {
			$inputs[] = $value[0];
			$expected[] = $value[1];
		}
		$this->assertEquals($expected, $stemmer->stemWords($inputs));
	}
	
	public function testStemWordsInASentence()
	{
		$sentence = "the quick brown fox";
		$expected = array('the', 'quick', 'brown', 'fox');
		$stemmer = new wotsit_feature_Stemmer();
		$this->assertEquals($expected, $stemmer->stemWords($sentence));
	}
	
	public function testWithEmptyWord()
	{
		$stemmer = new wotsit_feature_Stemmer();
		$this->assertFalse($stemmer->stemWord(''));
		$this->assertFalse($stemmer->stemWords(''));
	}
	
	public function testWithPosessiveS()
	{
		$input = "jon's";
		$expected = 'jon';
		$stemmer = new wotsit_feature_Stemmer();
		$this->assertEquals($expected, $stemmer->stemWord($input));
	}
	
	public function testWithHyphenated()
	{
		$input = "free-swimming";
		$expected = 'free-swim';
		$stemmer = new wotsit_feature_Stemmer();
		$this->assertEquals($expected, $stemmer->stemWord($input));
	}
	
	
	private function getWordList()
	{
		$data = array();
		$words = fopen(dirname(__FILE__) . '/../fixtures/stemmer.csv', 'r');
		while (($item = fgetcsv($words)) !== false) {
			$data[] = $item;
		}
		return $data;
	}
}