<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * A transient memory storage engine.
 * 
 * WARNING: All data in this storage engine will be lost when the script exits
 *
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_Memory implements wotsit_iStorage
{

    /**
     * @var array
     */
    protected $featuresCategories = array();

    /**
     * @var array
     */
    protected $categories = array();

    /**
     * Increases the count for the category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     */
    public function incrementFeatureCount(wotsit_Feature $feature, $category)
    {
        $key = $this->getKey($feature, $category);
        if (!array_key_exists($key, $this->featuresCategories)) {
            $this->featuresCategories[$key] = 0;
        }
        ++$this->featuresCategories[$key];
    }

    /**
     * Returns the count for a category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     * @return float The count associated with the specified feature and category
     */
    public function getFeatureCount(wotsit_Feature $feature, $category)
    {
        $count = 0;
        $key = $this->getKey($feature, $category);
        if (array_key_exists($key, $this->featuresCategories)) {
            $count = $this->featuresCategories[$key];
        }
        return $count;
    }

    /**
     * Increases the count for the specified category
     *
     * @param string $category The category
     */
    public function incrementCategoryCount($category)
    {
        if (!array_key_exists($category, $this->categories)) {
            $this->categories[$category] = 0;
        }
        ++$this->categories[$category];
    }

    /**
     * Returns the count for the specified category
     *
     * @param string $category
     * @return float The count for the specified category
     */
    public function getCategoryCount($category)
    {
        $count = 0;
        if (array_key_exists($category, $this->categories)) {
            $count = $this->categories[$category];
        }
        return $count;
    }

    /**
     * Returns the sum of all category counts
     *
     * @return float The sum of all category counts
     */
    public function getTotalCount()
    {
        return array_sum($this->categories);
    }

    /**
     * Returns an array of categories
     *
     * @return array The categories
     */
    public function getCategories()
    {
        return array_keys($this->categories);
    }

    /**
     * @param wotsit_Feature $feature
     * @param string $category
     * @return string
     */
    private function getKey(wotsit_Feature $feature, $category)
    {
        return $feature->getValue() . '-' . $category;
    }
}