<?php
/**
 * @package wotsit
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 * @version SVN: $Id$
 */

/**
 * Interface for a feature extrator
 * 
 * feature extractors should take an input and return an array of wotsit_Feature objects
 * for features found within the input.
 * 
 * There is no restriction on the type of input
 *
 * @package wotsit
 */
interface wotsit_iExtractFeatures
{
    /**
     *
     * @param mixed $input
     * @return array
     */
    public function getFeatures($input);

}