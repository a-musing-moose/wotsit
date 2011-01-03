<?php
/**
 * @package wotsit
 * @subpackage classifier
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * This class contains a Naive Bayesian classifier implementation
 *
 * @package wotsit
 * @subpackage classifier
 */
class wotsit_classifier_Fisher extends wotsit_Classifier implements wotsit_iClassifier
{

	/**
	 * @param wotsit_iExtractFeatures $featureExtractor
	 */
	public function __construct(wotsit_iExtractFeatures $featureExtractor, wotsit_iStorage $storage)
	{
		parent::__construct($featureExtractor, $storage);
	}

	protected function featureProbability(wotsit_Feature $feature, $category)
	{
		$probability = 0;
		$probabilityInChosenCategory = parent::featureProbability($feature, $category);

		if($probabilityInChosenCategory != 0){
			$frequencySum = 0.0;
			foreach($this->storage->getCategories() as $aCategory) {
				$frequencySum += parent::featureProbability($feature, $aCategory);
			}
			$probability = $probabilityInChosenCategory/$frequencySum;
		}
		return $probability;
	}

	/**
	 * Returns the probability that the given $item is in the given $category
	 *
	 * @param string $item
	 * @param string $category
	 */
	public function getProbability($item, $category){
		$probability = 1;
		$features = $this->featureExtractor->getFeatures($item);
		foreach($features as $feature) {
			$probability *= $this->weightedProbability($feature, $category);
		}
		$fscore = -2.0 * log($probability);
		$probability = $this->inverseChi2($fscore, count($features)*2);
		return $probability;
	}

	/**
	 * Calculates the inverse Chi^2
	 *
	 * @param float $chi
	 * @param float $degreesOfFreedom
	 * @return float
	 */
	public function inverseChi2($chi, $degreesOfFreedom){
		$m = $chi / 2.0;
		$sum = exp(-$m);
		$term = $sum;

		for($i=1; $i < ($degreesOfFreedom/2); $i++){
			$term *= $m/$i;
			$sum += $term;
		}

		return min($sum, 1.0);
	}
}