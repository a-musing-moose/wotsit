<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * DBM storage object
 * 
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_Dbm implements wotsit_iStorage
{

    const CATEGORY_LIST_KEY = 'WOTSIT_CATEGORIES';

    const FEATURE_KEY = "WOTSIT_%s:%s";

    /**
     * Handle to the DBM file resource
     *
     * @var resource
     */
    private $dbm;
    
    /**
     * @var string
     */
    private $filepath;
    
    /**
     * @param string $filePath
     * @param int $expiration
     */
    public function __construct($filePath)
    {
        if (!extension_loaded('dba')) {
            throw new RuntimeException("The dbm extension is not loaded");
        }
        $this->filepath = $filePath;
        $this->openDbmFile();
    }

    public function  __destruct()
    {
        dba_sync($this->dbm);
        dba_optimize($this->dbm);
        dba_close($this->dbm);
    }
    
    /**
     * @return void
     */
    private function openDbmFile()
    {
        $this->dbm = dba_open($this->filepath, 'c');
    }

    /**
     * Increases the count for the category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     */
    public function incrementFeatureCount(wotsit_Feature $feature, $category)
    {
        $count = $this->getFeatureCount($feature, $category) + 1;
        dba_replace($this->getFeatureKey($feature, $category), $count, $this->dbm);
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
        $count = dba_fetch($this->getFeatureKey($feature, $category), $this->dbm);
        if (false === $count) {
            $count = 0;
        }
        return (int)$count;
    }

    /**
     * @param wotsit_Feature $feature
     * @param string $category
     * @return string
     */
    private function getFeatureKey(wotsit_Feature $feature, $category)
    {
        return sprintf(self::FEATURE_KEY, $category, $feature->getValue());
    }

    /**
     * Increases the count for the specified category
     *
     * @param string $category The category
     * @return void
     */
    public function incrementCategoryCount($category)
    {
        $this->ensureCategory($category);
        $count = $this->getCategoryCount($category) + 1;
        dba_replace($category, $count, $this->dbm);
    }

    /**
     * Returns the count for the specified category
     *
     * @param string $category
     * @return float The count for the specified category
     */
    public function getCategoryCount($category)
    {
        $count = dba_fetch($category, $this->dbm);
        if (false === $count) {
            $count = 0;
        }
        return (int)$count;
    }

    /**
     * Returns the sum of all category counts
     *
     * @return float The sum of all category counts
     */
    public function getTotalCount()
    {
        $totalCount = 0;
        foreach ($this->getCategories() as $category) {
            $totalCount += $this->getCategoryCount($category);
        }
        return $totalCount;
    }

    /**
     * Returns an array of categories
     *
     * @return array The categories
     */
    public function getCategories()
    {
        $categories = array();
        if (dba_exists(self::CATEGORY_LIST_KEY, $this->dbm)) {
            $categories = unserialize(dba_fetch(self::CATEGORY_LIST_KEY, $this->dbm));
        }
        return array_values($categories);
    }

     /**
     * Ensures a category is present in the dbm file
     *
     * @param string $category
     */
    private function ensureCategory($category)
    {
        $data = array();
        if (dba_exists(self::CATEGORY_LIST_KEY, $this->dbm)) {
            $data = unserialize(dba_fetch(self::CATEGORY_LIST_KEY, $this->dbm));
        }
        $data[$category] = $category;
        dba_replace(self::CATEGORY_LIST_KEY, serialize($data), $this->dbm);
    }
}