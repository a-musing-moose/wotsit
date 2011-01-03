<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * A single, weighted feature
 *
 * @package wotsit
 */
class wotsit_Feature
{

    private $value;
    private $weight;

    /**
     * @param string $value
     * @param float $weight
     */
    public function __construct($value, $weight = 1.0)
    {
        $this->value = (string)$value;
        $this->weight = (float)$weight;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }
}