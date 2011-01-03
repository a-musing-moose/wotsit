<?php
/**
 * @package wotsit
 * @subpackage feature
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * The filter_LinkExtractor class implements the filter_iExtractFeatures interface
 *
 * This class extracts links from the passed in document
 *
 * @package wotsit
 * @subpackage feature
 */
class wotsit_feature_LinkExtractor implements wotsit_iExtractFeatures
{

    const URL_REGEX = "((([hH][tT][tT][pP][sS]?|[fF][tT][pP])\:\/\/)?([\w\.\-]+(\:[\w\.\&%\$\-]+)*@)?((([^\s\(\)\<\>\\\"\.\[\]\,@;:]+)(\.[^\s\(\)\<\>\\\"\.\[\]\,@;:]+)*(\.[a-zA-Z]{2,4}))|((([01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}([01]?\d{1,2}|2[0-4]\d|25[0-5])))(\b\:(6553[0-5]|655[0-2]\d|65[0-4]\d{2}|6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{0,3}|0)\b)?((\/[^\/][\w\.\,\?\'\\\/\+&%\$#\=~_\-@]*)*[^\.\,\?\"\'\(\)\[\]!;<>{}\s\x7F-\xFF])?)";

    protected $linkWeight = 1.2;

    /**
     * Returns an array of features found in the document
     *
     * @param string $input The document to extract features from
     * @return array An array of features extracted from the provided document
     */
    public function getFeatures($input){

        $features = $this->getLinkFeatures($input);
        return $features;
    }

    /**
     *
     * @param float $weight
     * @return wotsit_feature_CommentExtractor
     */
    public function setLinkWeight($weight = 1.2)
    {
        $this->linkWeight = (float)$weight;
        return $this;
    }

    /**
     * Returns an array of link features
     *
     * @param string $input
     * @return array
     */
    protected function getLinkFeatures($input)
    {
        $features = array();
        if (preg_match_all(self::URL_REGEX, $input, $matches) && isset($matches[0])) {
            foreach ($matches[0] as $link) {
                $link = strtolower($link);
                $features[$link] = new wotsit_Feature($link, $this->linkWeight);
            }
        }
        return array_values($features);
    }
}