<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * An interface for wotsit storage backends
 *
 * @package wotsit
 */
interface wotsit_iStorage
{
    /**
     * Increases the count for the category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     */
    public function incrementFeatureCount(wotsit_Feature $feature, $category);

    /**
     * Returns the count for a category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     * @return int The count associated with the specified feature and category
     */
    public function getFeatureCount(wotsit_Feature $feature, $category);

    /**
     * Increases the count for the specified category
     *
     * @param string $category The category
     */
    public function incrementCategoryCount($category);

    /**
     * Returns the count for the specified category
     *
     * @param string $category
     * @return int The count for the specified category
     */
    public function getCategoryCount($category);

    /**
     * Returns the sum of all category counts
     *
     * @return int The sum of all category counts
     */
    public function getTotalCount();

    /**
     * Returns an array of categories
     *
     * @return array The categories
     */
    public function getCategories();

}