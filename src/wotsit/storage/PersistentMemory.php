<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * Holds the entire training data in memory but flushes to disk on exit
 * 
 * WARNING: Do not use in a situation where more than on process is accessing the stored data as changes from
 * one process WILL overwrite others.
 *
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_PersistentMemory extends wotsit_storage_Memory
{

    /**
     * @var string
     */
    private $fileName;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        if (file_exists($this->fileName)) {
            $this->restore();
        }
    }

    /**
     * Ensures that data is serialized to disk
     */
    public function __destruct()
    {
        $this->persist();
    }

    /**
     * Serializes the data to disk
     */
    private function persist()
    {
        $data = array(
            'categories' => $this->categories,
            'featuresCategories' => $this->featuresCategories
        );
        file_put_contents($this->fileName, serialize($data), LOCK_EX);
    }

    /**
     * Restores serialized data from disk
     */
    private function restore()
    {
        $data = unserialize(file_get_contents($this->fileName));
        $this->categories = $data['categories'];
        $this->featuresCategories = $data['featuresCategories'];
    }

}