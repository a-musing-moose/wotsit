<?php
/**
 * @package wotsit
 * @subpackage classifier
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * This class contains a Naive Bayesian classifier implementation
 *
 * @package wotsit
 * @subpackage classifier
 */
class wotsit_classifier_NaiveBayesian extends wotsit_Classifier implements wotsit_iClassifier
{

    /**
     * Constructor takes a filter_iExtractFeatures object which is used to extract the features for any gieven document
     *
     * @param filter_iExtractFeatures $objExtractor
     */
    public function __construct(wotsit_iExtractFeatures $featureExtractor, wotsit_iStorage $storage)
    {
        parent::__construct($featureExtractor, $storage);
    }

    /**
     * Return the weighted probability that features within a document fit within the specified category
     *
     * @param string $item The item to categories
     * @param string $feature The category to test it in
     * @return float The probablity that $item fits within $category
     */
    protected function getDocumentProbability($item, $category)
    {
        $features = $this->featureExtractor->getFeatures($item);
        $documentProbability = 1.0;
        foreach($features as $feature) {
            $documentProbability *= $this->weightedProbability($feature, $category);
        }
        return $documentProbability;
    }

    /**
     * Returns the product of Pr($item, $category) and Pr($category)
     *
     * @param string $item The item
     * @param string $category The category
     * @return float The probability
     */
    protected function getProbability($item, $category)
    {
        $categoryProbability = $this->storage->getCategoryCount($category) / $this->storage->getTotalCount();
        $documentProbability = $this->getDocumentProbability($item, $category);
        return $categoryProbability * $documentProbability;
    }
}