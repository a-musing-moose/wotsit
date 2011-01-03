<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * Provides a convenient way to use multiple extractors as a single one
 *
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_ExtractorCollection implements wotsit_iExtractFeatures
{

    /**
     * @var array
     */
    private $extractors = array();

    /**
     * Adds an extractor to the list
     *
     * @param wotsit_iExtractFeatures$extractor
     * @return wotsit_feature_ExtractorCollection
     */
    public function addExtractor(wotsit_iExtractFeatures $extractor)
    {
        $this->extractors[] = $extractor;
        return $this;
    }

    /**
     * Returns all features extracted from the input
     *
     * @param mixed $input
     * @return array
     */
    public function getFeatures($input)
    {
        $features = array();
        foreach ($this->extractors as $extractor) {
            foreach ($extractor->getFeatures($input) as $feature) {
                $features[] = $feature;
            }
        }
        return $features;
    }

}