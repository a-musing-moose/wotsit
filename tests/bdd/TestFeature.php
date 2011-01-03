<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
include_once 'PHPUnit/Extensions/Story/TestCase.php';
require_once dirname(__FILE__) . '/../../src/wotsit/Feature.php';

/**
 * @package wotsit
 */
class wotsit_TestFeature extends PHPUnit_Extensions_Story_TestCase
{

    public function testNewFeaturesHaveADefaultWeightOfOne()
    {
        $this->given('a new feature', 'value')
        	 ->then("weight must be", 1.0);
    }

    public function testNewFeaturesRememberTheirValue()
    {
    	$value = 'value';
        $this->given('a new feature', $value)
        	 ->then("value must be", $value);
    }

    public function testWithCustomWeight()
    {
        $this->given('a new weighted feature', 2.5)
        	 ->then("weight must be", 2.5);
    }
    
    ////////////////////////////
    
	public function runGiven(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'a new feature':
                $world['feature'] = new wotsit_Feature($arguments[0]);
                break;
            case 'a new weighted feature':
                $world['feature'] = new wotsit_Feature('', $arguments[0]);
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }

    public function runWhen(&$world, $action, $arguments)
    {
    	$this->notImplemented($action);
    }

    public function runThen(&$world, $action, $arguments)
    {
        $action = strtolower($action);
        switch ($action) {
            case 'weight must be':
                $this->assertEquals($arguments[0], $world['feature']->getWeight());
                break;
            case 'value must be':
                $this->assertEquals($arguments[0], $world['feature']->getValue());
                break;
            default:
                $this->notImplemented($action);
            break;
        }
    }

}