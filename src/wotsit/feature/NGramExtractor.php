<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * The wotsit_feature_NGramExtractor class implements the filter_iExtractFeatures interface
 *
 * Like the WordExtract, this class provides a simple split on whitespace characters with word length constraints.
 * The NGram extractor takes it one step further and splits the words into n-gram of a defined length. for example
 * with the ngram length set to 3 the word 'jon' would result in these tokens:
 * 
 * <code>
 * [0] => '  j';
 * [1] => ' jo';
 * [2] => 'jon';
 * [3] => 'on ';
 * [4] => 'n  ';
 * </code>
 *
 * n-grams are more tolerant to mis-spellings and intentional typos but at the cost of additional computation and many more tokens
 *
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_NGramExtractor extends wotsit_feature_WordExtractor
{

	protected $nGramLength;

	/**
	 * @param int $minimumLength The minimum word length
	 * @param int $maximumLength The maximum word length
	 */
	public function __construct($nGramLength = 3, $minimumLength = 2, $maximumLength = 20, wotsit_feature_Stemmer $stemmer = null)
	{
		parent::__construct($minimumLength, $maximumLength, $stemmer);
		$this->nGramLength = $nGramLength;
	}

	/**
	 * Returns an array of features found in the document
	 *
	 * @param string $input The document to extract features from
	 * @return array An array of wotsit_Feature objects extracted from the provided document
	 */
	public function getFeatures($input)
	{
		$decodedInput = strip_tags($input); //just in case
		$tokens = $this->tokenize($decodedInput);
		$nGrams = $this->createNGrams($tokens);
		return $this->tokensToFeatures($nGrams);
	}

	/**
	 * Converts and array of words into an array of n-grams
	 * 
	 * e.g. JON becomes:
	 * <code>
	 * [0] => '  J'
	 * [1] => ' JO'
	 * [2] => 'JON'
	 * [3] => 'ON '
	 * [4] => 'N  '
	 * </code>
	 * 
	 * @param array $tokens
	 * @return array
	 */
	protected function createNGrams(array $tokens)
	{
		$nGrams = array();
		foreach ($tokens as $token) {
			$padding = str_pad('', $this->nGramLength - 1, ' ');
			$token = $padding . $token . $padding;
			$len = strlen($token) - ($this->nGramLength - 1);
			for($pos = 0; $pos<$len; $pos++) {
				$nGram = substr($token, $pos, $this->nGramLength);
				$nGrams[$nGram] = $nGram;
			}
		}
		return $nGrams;
	}
}