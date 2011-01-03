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
require_once dirname(__FILE__) . '/../../../src/wotsit/storage/PersistentMemory.php';

/**
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_TestPersistentMemory extends PHPUnit_Framework_TestCase
{

    private $filePath;

    public function setUp()
    {
        //$this->filePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'temp.data';
        $this->filePath = tempnam("/tmp", "wotsit");
    	@unlink($this->filePath);
    }

	public function tearDown()
    {
        @unlink($this->filePath);
    }

    public function testCategoryStartsAtZero()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $count = $store->getCategoryCount('category');
        $this->assertEquals(0, $count);
        unset($store);
    }

    public function testCategoryIncrements()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $store->incrementCategoryCount('category');
        $count = $store->getCategoryCount('category');
        $this->assertEquals(1, $count);
        unset($store);
    }

    public function testGetCategories()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $expected = array('cat1', 'cat2', 'cat3');
        foreach ($expected as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $categories = $store->getCategories();
        $this->assertEquals($expected, $categories);
        unset($store);
    }

    public function testFeatureStartsAtZero()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $count = $store->getFeatureCount(new wotsit_Feature('test'), 'category');
        $this->assertEquals(0, $count);
        unset($store);
    }

    public function testFeatureIncrements()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $feature = new wotsit_Feature('test');
        $store->incrementFeatureCount($feature, 'category');
        $count = $store->getFeatureCount( $feature, 'category');
        $this->assertEquals(1, $count);
        unset($store);
    }

    public function testGetTotals()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $expected = 3;
        $categories = array('cat1', 'cat2', 'cat3');
        foreach ($categories as $cat) {
            $store->incrementCategoryCount($cat);
        }
        $total = $store->getTotalCount();
        $this->assertEquals($expected, $total);
        unset($store);
    }

    public function testIsCategoryPersistent()
    {
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $store->incrementCategoryCount('category');
        unset($store);
        $this->assertFileExists($this->filePath);
        $store = new wotsit_storage_PersistentMemory($this->filePath);
        $count = $store->getCategoryCount('category');
        $this->assertEquals(1, $count);
        unset($store);
    }

}