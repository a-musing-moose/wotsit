<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../../src/wotsit/Feature.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iStorage.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/storage/Memory.php';

/**
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_TestMemory extends PHPUnit_Framework_TestCase
{

    public function testCategoryStartsAtZero()
    {
        $store = new wotsit_storage_Memory();
        $count = $store->getCategoryCount('category');
        $this->assertEquals(0, $count);
    }

    public function testCategoryIncrements()
    {
        $store = new wotsit_storage_Memory();
        $store->incrementCategoryCount('category');
        $count = $store->getCategoryCount('category');
        $this->assertEquals(1, $count);
    }

    public function testGetCategories()
    {
        $store = new wotsit_storage_Memory();
        $expected = array('cat1', 'cat2', 'cat3');
        foreach ($expected as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $categories = $store->getCategories();
        $this->assertEquals($expected, $categories);
    }

    public function testFeatureStartsAtZero()
    {
        $store = new wotsit_storage_Memory();
        $count = $store->getFeatureCount(new wotsit_Feature('test'), 'category');
        $this->assertEquals(0, $count);
    }

    public function testFeatureIncrements()
    {
        $store = new wotsit_storage_Memory();
        $feature = new wotsit_Feature('test');
        $store->incrementFeatureCount($feature, 'category');
        $count = $store->getFeatureCount( $feature, 'category');
        $this->assertEquals(1, $count);
    }

    public function testGetTotals()
    {
        $store = new wotsit_storage_Memory();
        $expected = 3;
        $categories = array('cat1', 'cat2', 'cat3');
        foreach ($categories as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $total = $store->getTotalCount();
        $this->assertEquals($expected, $total);
    }

}