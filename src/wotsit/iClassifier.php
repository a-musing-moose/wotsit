<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * A interface to define classifiers
 *
 * @package wotsit
 */
interface wotsit_IClassifier
{
	
	/**
     * Returns the category that the item best fits in
     *
     * If we are unsure (i.e.) the best fit is below the threshold then we return the default category
     *
     * @param mixed $input The item to classify
     * @param string $default The default to return if we are unsure
     * @return string
     */
	public function classify($input, $default = null);

    /**
     * Returns an array of all category probabilities
     *
     * The array is keyed by the category and is sorted in descending order
     *
     * @param mixed $input The item to classify
     * @return array
     */
    public function classifications($input);
	
	/**
     * Adds an item to the filter with a given category
     *
     * @param mixed $input The string to extract features from
     * @param string $category The category it should be placed in
     */
	public function train($input, $category);
	
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
    public function setThreshold($category, $threshold);

    /**
     * Returns the threshold for the specified category
     *
     * @param string $category The category
     * @return float The threshold for the given category (default 1.0)
     */
    public function getThreshold($category);
	
}