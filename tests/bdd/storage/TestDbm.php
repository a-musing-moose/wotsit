<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */
include_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/iStorage.php';
require_once dirname(__FILE__) . '/../../../src/wotsit/storage/Dbm.php';

/**
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_TestDbm extends PHPUnit_Extensions_Story_TestCase
{

	public function testCategoryCountStartsAtZeroWhenNothingHasBeenAdded()
    {
        $this->given('a new store')
        	 ->then('category count must be', 0);
    }

    public function testCategoryIncrementsByOne()
    {
    	$this->given('a new store')
    		 ->when('category is incremented')
        	 ->then('category count must be', 1);
        $this->given('a new store')
    		 ->when('category is incremented')
    		 ->when('category is incremented')
        	 ->then('category count must be', 2);
    }

    public function testAllIncrementedCategoriesAreFound()
    {
    	$this->given('a new store')
    		 ->when('a category is incremented', 'cat1')
    		 ->and('a category is incremented', 'cat2')
    		 ->and('a category is incremented', 'cat3')
    		 ->then('categories must contain', 'cat1', 'cat2', 'cat3');
    }



    public function testFeaturesInANewStoreStartsAtZero()
    {
    	$this->given('a new store')
    		 ->then('feature count must be', 0);
    }



    public function testIncrementingAFeatureWithinACategoryIncrementsTheFeatureCountInThatCategory()
    {
    	$this->given('a new store')
    	     ->when('feature count in a category is incremented')
    		 ->then('feature count must be', 1);
    }

    public function testThatWhenCategoriesAreIncrementedThenTheCategoryTotalIsCorrectlyCalculated()
    {
    	$this->given('a new store')
    		 ->when('a category is incremented', 'cat1')
    		 ->and('a category is incremented', 'cat2')
    		 ->and('a category is incremented', 'cat3')
    		 ->then('categories total must be', 3);

    	$this->given('a new store')
    		 ->when('a category is incremented', 'cat1')
    		 ->and('a category is incremented', 'cat2')
    		 ->and('a category is incremented', 'cat2')
    		 ->and('a category is incremented', 'cat3')
    		 ->then('categories total must be', 4);
    }

	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new store':
            	@unlink('test.dbm');
                $world['store'] = new wotsit_storage_Dbm('test.dbm');
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }

    public function runWhen(&$world, $action, $arguments)
    {
    	$action = strtolower($action);
    	switch ($action) {
    		case 'category is incremented':
    			$world['store']->incrementCategoryCount('category');
    			break;
    		case 'a category is incremented':
    			$world['store']->incrementCategoryCount($arguments[0]);
    			break;
    		case 'feature count in a category is incremented':
    			$world['store']->incrementFeatureCount($this->getMockedFeature('value'), 'category');
    			break;
    		default:
    			$this->notImplemented($action);
    			break;
    	}
    }

    public function runThen(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'category count must be':
                $this->assertEquals($arguments[0], $world['store']->getCategoryCount('category'));
                break;
            case 'categories must contain':
            	$categories = $world['store']->getCategories();
            	foreach ($arguments as $argument) {
            		$contained = in_array($argument, $categories);
            		$this->assertTrue($contained);
            	}
            	break;
            case 'feature count must be':
            	$feature = $this->getMockedFeature('value');
            	$this->assertEquals($arguments[0], $world['store']->getFeatureCount($feature, 'category'));
            	break;
            case 'categories total must be':
            	$this->assertEquals($arguments[0], $world['store']->getTotalCount());
            	break;
            default:
                $this->notImplemented($action);
            break;
        }
    }

    private function getMockedFeature($value)
    {
    	$mockFeature = $this->getMock('wotsit_Feature', array('getValue'), array(), '', false);
    	$mockFeature->expects($this->any())
             		->method('getValue')
             		->will($this->returnValue($value));
        return $mockFeature;
    }

    private $storage;
    private $filename;

    public function tearDown()
    {
    	unlink('test.dbm');
    }

    public function setUp()
    {
        if (!extension_loaded('dba')) {
            $this->markTestSkipped("DBA extension not available");
        }
    	@unlink('test.dbm');
    }

}