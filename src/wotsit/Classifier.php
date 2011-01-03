<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * The abstract base class for Classifiers
 *
 * @package wotsit
 */
abstract class wotsit_Classifier implements wotsit_iClassifier
{

    /**
     * @var wotsit_iExtractFeatures
     */
    protected $featureExtractor;

    /**
     * @var wotsit_iStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected $thresholds;

    /**
     * Constructor that takes a filter_iExtractFeature as a parameter to extract features
     *
     * @param wotsit_iExtractFeatures $featureExtractor
     */
    public function __construct(wotsit_iExtractFeatures $featureExtractor, wotsit_iStorage $storage)
    {
        $this->featureExtractor = $featureExtractor;
        $this->storage = $storage;
        $this->thresholds = array();
    }

    /**
     * Returns the probability that the given item fits within the specified category
     *
     * Must be implement to derived classes to allow for classification
     *
     * @param string $item The item to test
     * @param string $category The category to test it in
     */
    protected abstract function getProbability($item, $category);

    /**
     * Adds an item to the filter with a given category
     *
     * @param mixed $input The string to extract features from
     * @param string $category The category it should be placed in
     */
    public function train($input, $category)
    {
        foreach($this->featureExtractor->getFeatures($input) as $feature) {
            $this->storage->incrementFeatureCount($feature, $category);
        }
        $this->storage->incrementCategoryCount($category);
    }




    /**
     * Returns the category that the item best fits in
     *
     * If we are unsure (i.e.) the best fit is below the threshold then we return the default category
     *
     * @param mixed $input The item to classify
     * @param string $default The default to return if we are unsure
     * @return string
     */
    public function classify($input, $default = null)
    {
        $probabilities = $this->classifications($input);
        reset($probabilities);
        $bestCategory = key($probabilities);
        $max = $probabilities[$bestCategory];
        array_shift($probabilities);

        //check the best match is above the threshold factor compared to other categories
        foreach ($probabilities as $category => $probability) {
            
            if (($probability * $this->getThreshold($bestCategory)) > $max) {
                return $default;
            }
        }
        return $bestCategory;
    }

    /**
     * Returns an array of category probabilities
     *
     * The array is keyed by the category and is sorted in descending order
     *
     * @param mixed $input The item to classify
     * @return array
     */
    public function classifications($input)
    {
        $probabilities = array();
        foreach ($this->storage->getCategories() as $category) {
            $probabilities[$category] = $this->getProbability($input, $category);
        }
        arsort($probabilities, SORT_NUMERIC);
        return $probabilities;
    }

    /**
     * Sets the threshold for a given category
     *
     * If the probability that an item fits within the given category is less that
     * the specified threshold then we are not sure that this is correct.
     *
     * Default threshold is 1.0
     *
     * @param string $category The category
     * @param float $threshold The threshold
     */
    public function setThreshold($category, $threshold)
    {
        $this->thresholds[$category] = $threshold;
    }

    /**
     * Returns the threshold for the specified category
     *
     * @param string $category The category
     * @return float The threshold for the given category (default 1.0)
     */
    public function getThreshold($category)
    {
        $threshold = 1.0;
        if (array_key_exists($category, $this->thresholds)) {
            $threshold = $this->thresholds[$category];
        }
        return $threshold;
    }

    /**
     * Returns that probablity that the given feature is in the given category
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     * @return float The probability that the given feature is in the given category
     */
    protected function featureProbability(wotsit_Feature $feature, $category)
    {
        $categoryCount = $this->storage->getCategoryCount($category);
        if ($categoryCount == 0) {
            return 0.0;
        }
        return $this->storage->getFeatureCount($feature, $category) / $categoryCount;
    }

    /**
     * Returns the weighted probablity that a given feature is in a given category
     *
     * @param string $feature The feature
     * @param string $category The category
     * @param float $weight The weighting to apply (default = 1.0)
     * @param float $assumedProbability The assumed probability (default 0.5)
     * @return float The weighted probability that the given feature is in the given category
     */
    protected function weightedProbability(wotsit_Feature $feature, $category, $assumedProbability = 0.5){
        $baseProbability = $this->featureProbability($feature, $category);
        $totals = 0;
        foreach ($this->storage->getCategories() as $aCategory) {
            $totals += $this->storage->getFeatureCount($feature, $aCategory);
        }
        $weight = $feature->getWeight();
        $weightedProbability = (($weight*$assumedProbability) + ($totals*$baseProbability)) / ($weight+$totals);
        return $weightedProbability;
    }   
}