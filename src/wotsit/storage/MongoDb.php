<?php
/**
 * @package wotsit
 * @subpackage storage
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 */

/**
 * A storage engine which makes use of MongoDB
 *
 * This storage engine is persistent and fast
 *
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_MongoDb implements wotsit_iStorage
{

    /**
     * @var MongoDB
     */
    private $db;

    /**
     * @var string
     */
    private $categoryCollection = 'wotsit.categories';


    /**
     * @var boolean
     */
    private $categoryIndexed = false;

    /**
     * @var string
     */
    private $featureCollection = 'wotsit.features';

    /**
     * @var boolean
     */
    private $featureIndexed = false;


    /**
     * @param MongoDB $db
     */
    public function  __construct(MongoDB $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $name
     * @return wotsit_storage_MongoDb
     */
    public function setCategoryCollection($name = 'wotsit.categories')
    {
        $this->categoryCollection = $name;
        return $this;
    }

    /**
     * @param string $name
     * @return wotsit_storage_MongoDB
     */
    public function setFeatureCollection($name = 'wotsit.features')
    {
        $this->featureCollection = $name;
        return $this;
    }
    
    /**
     * @return MongoCollection
     */
    private function getCategoryCollection()
    {
        $collection = $this->db->selectCollection($this->categoryCollection);
        if (!$this->categoryIndexed) {
            $collection->ensureIndex(array('category' => 1), array('unique' => true, 'background' => true));
            $this->categoryIndexed = true;
        }
        return $collection;
    }

    /**
     * @return MongoCollection
     */
    private function getFeatureCollection()
    {
        $collection =  $this->db->selectCollection($this->featureCollection);
        if (!$this->featureIndexed) {
            $collection->ensureIndex(array('category' => 1, 'feature' => 1), array('unique' => true, 'background' => true));
            $this->featureIndexed = true;
        }
        return $collection;
    }

    /**
     * Increases the count for the category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     */
    public function incrementFeatureCount(wotsit_Feature $feature, $category)
    {
        $criteria = array(
            'category' => $category,
            'feature'  => $feature->getValue()
        );
        $data = array(
            '$inc'    => array('count' => 1)
        );
        $options = array(
            'upsert' => true
        );
        $this->getFeatureCollection()->update($criteria, $data, $options);
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
        $criteria = array(
            'category' => $category,
            'feature'  => $feature->getValue()
        );
        $result = $this->getFeatureCollection()->findOne($criteria, array('count'));
        if (null != $result) {
            $count = $result['count'];
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
        $criteria = array(
            'category' => $category,
        );
        $data = array(
            '$inc'    => array('count' => 1)
        );
        $options = array(
            'upsert' => true
        );
        $this->getCategoryCollection()->update($criteria, $data, $options);
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
        $criteria = array(
            'category' => $category,
        );
        $result = $this->getCategoryCollection()->findOne($criteria, array('count'));
        if (null != $result) {
            $count = $result['count'];
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
        $count = 0;
        $cursor = $this->getCategoryCollection()->find(array(), array('count'));
        foreach ($cursor as $category) {
            $count += $category['count'];
        }
        return $count;
    }

    /**
     * Returns an array of categories
     *
     * @return array The categories
     */
    public function getCategories()
    {
        $categories = array();
        $cursor = $this->getCategoryCollection()->find(array());
        foreach ($cursor as $category) {
            $categories[] = $category['category'];
        }
        return $categories;
    }
}