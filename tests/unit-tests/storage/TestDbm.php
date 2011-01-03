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
require_once dirname(__FILE__) . '/../../../src/wotsit/storage/Dbm.php';

/**
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_TestDbm extends PHPUnit_Framework_TestCase
{

    public function setup()
    {
        if (!extension_loaded('dba')) {
            $this->markTestSkipped("DBA extension not available");
        }
        @unlink('temp.dbm');
    }

    public function tearDown()
    {
        @unlink('temp.dbm');
    }

    public function testCategoryStartsAtZero()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $count = $store->getCategoryCount('category');
        $this->assertEquals(0, $count);
    }

    public function testCategoryIncrements()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $store->incrementCategoryCount('category');
        $count = $store->getCategoryCount('category');
        $this->assertEquals(1, $count);
    }

    public function testGetCategories()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $expected = array('cat1', 'cat2', 'cat3');
        foreach ($expected as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $categories = $store->getCategories();
        $this->assertEquals($expected, $categories);
    }

    public function testFeatureStartsAtZero()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $count = $store->getFeatureCount(new wotsit_Feature('test'), 'category');
        $this->assertEquals(0, $count);
    }

    public function testFeatureIncrements()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $feature = new wotsit_Feature('test');
        $store->incrementFeatureCount($feature, 'category');
        $count = $store->getFeatureCount( $feature, 'category');
        $this->assertEquals(1, $count);
    }

    public function testGetTotals()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $expected = 3;
        $categories = array('cat1', 'cat2', 'cat3');
        foreach ($categories as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $total = $store->getTotalCount();
        $this->assertEquals($expected, $total);
    }

    public function testIsCategoryPersistent()
    {
        $store = new wotsit_storage_Dbm('temp.dbm');
        $store->incrementCategoryCount('category');
        unset($store);
        $this->assertFileExists('temp.dbm');
        $store = new wotsit_storage_Dbm('temp.dbm');
        $count = $store->getCategoryCount('category');
        $this->assertEquals(1, $count);
    }

}