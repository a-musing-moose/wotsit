<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */
require_once dirname(__FILE__) . '/../../src/wotsit/Feature.php';

/**
 * @package wotsit
 */
class wotsit_TestFeature extends PHPUnit_Framework_TestCase
{

    public function testWithDefaultWeight()
    {
        $expected = 1.0;
        $feature = new wotsit_Feature('value');
        $this->assertEquals($expected, $feature->getWeight());
    }

    public function testValue()
    {
        $expected = 'value';
        $feature = new wotsit_Feature($expected);
        $this->assertEquals($expected, $feature->getValue());
    }

    public function testWithCustomWeight()
    {
        $expected = 2.5;
        $feature = new wotsit_Feature('value', $expected);
        $this->assertEquals($expected, $feature->getWeight());
    }

}