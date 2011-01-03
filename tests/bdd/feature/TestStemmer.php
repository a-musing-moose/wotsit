<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/feature/Stemmer.php';

/**
 * @package wotsit
 */
class wotsit_feature_TestStemmer extends PHPUnit_Extensions_Story_TestCase
{
	
	/**
	 * @param string $word
	 * @param string $expected
	 */
	public function testCanStemAWord()
	{
		$this->given('a new stemmer');
		foreach ($this->getWordList() as $value) {
            $this->when('passed a word', $value[0])
                 ->then('the stemmed word is', $value[1]);
		}
	}

    public function testCanStemWords()
    {
        $inputs = array();
        $expected = array();
        foreach ($this->getWordList() as $value) {
			$inputs[] = $value[0];
			$expected[] = $value[1];
		}

        $this->given('a new stemmer')
             ->when('passed several words', $inputs)
             ->then('the stemmed words are', $expected);
    }

    public function testCanStemWordsInASentence()
    {
        $this->given('a new stemmer')
             ->when('passed several words', 'the quick brown fox')
             ->then('the stemmed words are', array('the', 'quick', 'brown', 'fox'));
    }

    public function testCanStemPossesives()
    {
        $this->given('a new stemmer')
             ->when('passed a word', "Jon's")
             ->then('the stemmed word is', 'jon');
    }

    public function testCanStemHyphenatedWords()
    {
        $this->given('a new stemmer')
             ->when('passed a word', "free-swimming")
             ->then('the stemmed word is', 'free-swim');
    }

    public function testReturnsFalseWhenNoWordIsPassed()
    {
        $this->given('a new stemmer')
             ->when('passed a word', "")
             ->then('the stemmed word is', false);
    }
	
	//HELPER FUNCTIONS
	
	private function getWordList()
	{
		$data = array();
		$words = fopen(dirname(__FILE__) . '/../fixtures/stemmer.csv', 'r');
		while (($item = fgetcsv($words)) !== false) {
			$data[] = $item;
		}
		return $data;
	}
	
	//BBD FUNCTIONS //
    
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new stemmer':
                $world['stemmer'] = new wotsit_feature_Stemmer();
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
    		case 'passed a word':
    			$world['word'] = $arguments[0];
    			break;
            case 'passed several words':
                $world['words'] = $arguments[0];
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
            case 'the stemmed word is':
                $stemmedWord = $world['stemmer']->stemWord($world['word']);
                $this->assertEquals($arguments[0], $stemmedWord);
                break;
            case 'the stemmed words are':
                $stemmedWords = $world['stemmer']->stemWords($world['words']);
                $this->assertEquals($arguments[0], $stemmedWords);
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }
	
}