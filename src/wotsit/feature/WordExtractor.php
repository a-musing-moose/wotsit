<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * The wotsit_feature_WordExtractor class implements the filter_iExtractFeatures interface
 *
 * This class provides a simple split on whitespace characters with word length constraints
 *
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_WordExtractor implements wotsit_iExtractFeatures
{

	protected $minimumLength;
	protected $maximumLength;

	/**
	 * @var wotsit_feature_Stemmer
	 */
	protected $stemmer;

	/**
	 * @param int $minimumLength The minimum word length
	 * @param int $maximumLength The maximum word length
	 */
	public function __construct($minimumLength = 2, $maximumLength = 20, wotsit_feature_Stemmer $stemmer = null)
	{
		$this->minimumLength = $minimumLength;
		$this->maximumLength = $maximumLength;
		$this->stemmer = $stemmer;
	}

	/**
	 * Returns an array of features found in the document
	 *
	 * @param string $input The document to extract features from
	 * @return array An array of features extracted from the provided document
	 */
	public function getFeatures($input)
	{
		$decodedInput = strip_tags($input); //just in case
		$tokens = $this->tokenize($decodedInput);
		return $this->tokensToFeatures($tokens);
	}

	/**
	 * Returns an array of words
	 *
	 * @todo Add a word stemmer?
	 *
	 * @param string $input
	 * @return array An array of words
	 */
	protected function tokenize($input)
	{
		$words = array();
		$tokens = preg_split('/[\s]+/', $input);

		foreach($tokens as $token){
			$length = strlen($token);
			if (($length >= $this->minimumLength) && ($length <= $this->maximumLength)) {
				$token = trim(strtolower($token));
				if (null !== $this->stemmer) {
					$token = $this->stemmer->stemWord($token);
				}
				$words[$token] = $token;
			}
		}
		return $words;
	}

	/**
	 * Converts an array of tokens into features
	 *
	 * @param array $tokens
	 * @return array
	 */
	protected function tokensToFeatures(array $tokens)
	{
		$features = array();
		foreach ($tokens as $token) {
			$features[] = new wotsit_Feature($token);
		}
		return $features;
	}
}