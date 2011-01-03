<?php
/**
 * @package wotsit
 * @subpackage storage
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

/**
 * SQLite based storage of data
 *
 * For some reason this is somewhat slow
 *
 * @package wotsit
 * @subpackage storage
 */
class wotsit_storage_Sqlite implements wotsit_iStorage
{

    const TABLE_FEATURE_CATEGORY = 'FeaturesCategories';
    const TABLE_CATEGORY = 'Categories';

    const GET_FEATURE_COUNT = 'SELECT Count FROM FeaturesCategories WHERE Feature = :feature AND Category = :category';
    const GET_CATEGORY_COUNT = "SELECT Count FROM Categories WHERE Category = :category";
    const GET_TOTAL_COUNT = 'SELECT sum(Count) as Count FROM Categories';
    const GET_CATEGORIES = 'SELECT Category FROM Categories';

    /**
     * Path to db file
     * @var string
     */
    private $filePath;

    /**
     * Instance of the database object
     * @var PDO
     */
    private $db;

    /**
     * Creates the sqlite storage object
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->checkStructure();
    }


    /**
     * Increases the count for the category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     */
    public function incrementFeatureCount(wotsit_Feature $feature, $category)
    {
        $count = $this->getFeatureCount($feature, $category);
        ++$count;
        $bindings = array(
            'Feature' => $feature->getValue(),
            'Category' => $category,
            'Count' => $count
        );
        $this->replaceInto(self::TABLE_FEATURE_CATEGORY, $bindings);
    }

    /**
     * Returns the count for a category of the specified feature
     *
     * @param wotsit_Feature $feature The feature
     * @param string $category The category
     * @return float The count associated with the specified feature and category
     */
    public function getFeatureCount(wotsit_Feature $feature, $category)
    {
        $bindings = array(
            ':feature' => $feature->getValue(),
            ':category' => $category);
        return (integer)$this->fetchField(self::GET_FEATURE_COUNT, $bindings);
    }

    /**
     * Increases the count for the specified category
     *
     * @param string $category The category
     */
    public function incrementCategoryCount($category)
    {
        $count = $this->getCategoryCount($category);
        ++$count;
        $this->replaceInto(self::TABLE_CATEGORY, array('Category' => $category, 'Count' => $count));
    }

    /**
     * Returns the count for the specified category
     *
     * @param string $category
     * @return float The count for the specified category
     */
    public function getCategoryCount($category)
    {
        $bindings = array(':category' => $category);
        return (integer)$this->fetchField(self::GET_CATEGORY_COUNT, $bindings);
    }

    /**
     * Returns the sum of all category counts
     *
     * @return float The sum of all category counts
     */
    public function getTotalCount()
    {
        return (integer)$this->fetchField(self::GET_TOTAL_COUNT);
    }

    /**
     * Returns an array of categories
     *
     * @return array The categories
     */
    public function getCategories()
    {
        return $this->fetchColumn(self::GET_CATEGORIES);
    }

    /**
     * Returns the data base object
     *
     * @return PDO
     */
    private function getDb()
    {
        if (!($this->db instanceof PDO)) {
            $this->db = new PDO('sqlite:' . $this->filePath);
        }
        return $this->db;
    }

    /** Returns a single field value
     *
     * @param string $sql The query to run
     * @param array $bindings Parameter values to bind into query
     * @return string
     */
    private function fetchField($sql, $bindings = array())
    {
        $row = $this->fetchRow($sql, $bindings);
        return ($row && count($row) > 0) ? array_shift($row) : null;
    }

    /**
     * Returns a row
     *
     * @param string $sql The query to run
     * @param array $bindings Parameter values to bind into query
     * @return array
     */
    private function fetchRow($sql, $bindings = array())
    {
        $statement = $this->runQuery($sql, $bindings);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a column of values as an array
     *
     * @param string $sql The query to run
     * @param array $bindings Parameter values to bind into query
     * @return array
     */
    private function fetchColumn($sql, $bindings = array())
    {
        $statement = $this->runQuery($sql, $bindings);
        $columnData = array();
        while ($value = $statement->fetchColumn()) {
            $columnData[] = $value;
        }
        return $columnData;
    }

    /**
     * Inserts data into a table
     *
     * @param string $tableName
     * @param array $bindings A Hash of field name to value
     * @return PDOStatement
     */
    private function replaceInto($tableName, $bindings)
    {
        // Extract fields and values from bindings
        $fields = array();
        $values = array();
        foreach ($bindings as $field => $value) {
            $fields[] = $this->quoteIdentifier($field);
            $values[] = '?';
        }
        // Construct SQL and execute
        $escapedTableName = $this->quoteIdentifier($tableName);
        $sql = sprintf("REPLACE INTO %s (%s) VALUES (%s)", $escapedTableName, implode(', ', $fields), implode(', ', $values));
        return $this->runQuery($sql, array_values($bindings));
    }

    /**
     * Executes an SQL query and returns the PDO statement object
     *
     * @param string $sql
     * @param array $bindings
     * @return PDOStatement
     */
    private function runQuery($sql, $bindings = array())
    {
        if (count($bindings) > 0) {
            // Use a prepared statement if bindings are set
            $statement = $this->getDb()->prepare($sql);
            if (empty($statement)) {
                throw new RuntimeException('Invalid SQL: ' . $sql);
            }
            $statement->execute($bindings);
        } else {
            // Execute a normal query
            $statement = $this->getDb()->query($sql);
            if (false === $statement) {
                throw new RuntimeException('Invalid SQL: ' . $sql);
            }
        }
        return $statement;
    }

    /**
     * Quotes a table or fieldname
     *
     * @param string $identifier
     * @return string
     */
    private function quoteIdentifier($identifier)
    {
        return sprintf("%s", $identifier);
    }

    /**
     * Checks the structure to ensure the tables exist
     *
     * @return void
     */
    private function checkStructure()
    {
        $tableCount = (int)$this->fetchField(
           "SELECT  COUNT(name)
            FROM    sqlite_master
            WHERE   type = 'table'
            AND     (name = :catName OR name = :featName)",
           array('catName' => 'Categories', 'featName' => 'FeaturesCategories')
        );
        if ($tableCount != 2) {
            $this->createStructure();
        }
    }

    /**
     * Creates the table structure
     * @return void
     */
    private function createStructure()
    {
        $this->runQuery('CREATE TABLE Categories ( Category TEXT, Count INTEGER)');
        $this->runQuery('CREATE UNIQUE INDEX CategoryIndex ON Categories(Category)');
        $this->runQuery('CREATE TABLE FeaturesCategories ( Category TEXT, Feature TEXT, Count INTEGER)');
        $this->runQuery('CREATE UNIQUE INDEX FeatureCategoryIndex ON FeaturesCategories(Feature, Category)');
    }
}