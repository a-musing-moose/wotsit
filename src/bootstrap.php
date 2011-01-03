<?php
/**
 * @author Jonathan Moss <jonathan.moss@tangentone.com.au>
 * @copyright 2010 Tangent/One Au
 */

Phar::mapPhar();

if (php_sapi_name() == 'cli' && isset ($argv[0])) {
    $runner = new CliRunner();
    $runner->processParameters($argv);
} else {
    //register the autoloader
    spl_autoload_register(array('WotsitAutoloader', 'load'));
}

/**
 * Autoloader for the wotsit package
 */
class WotsitAutoloader
{

    /**
     * A static array of classes
     *
     * @var array
     */
    private static $classes = array(
        'wotsit_classifier_NaiveBayesian'       => 'phar://Wotsit/src/wotsit/classifier/NaiveBayesian.php',
        'wotsit_classifier_Fisher'              => 'phar://Wotsit/src/wotsit/classifier/Fisher.php',
        'wotsit_feature_ExtractorCollection'    => 'phar://Wotsit/src/wotsit/feature/ExtractorCollection.php',
        'wotsit_feature_LinkExtractor'          => 'phar://Wotsit/src/wotsit/feature/LinkExtractor.php',
    	'wotsit_feature_NGramExtractor'         => 'phar://Wotsit/src/wotsit/feature/NGramExtractor.php',
        'wotsit_feature_WordExtractor'          => 'phar://Wotsit/src/wotsit/feature/WordExtractor.php',
        'wotsit_storage_Dbm'                    => 'phar://Wotsit/src/wotsit/storage/Dbm.php',
        'wotsit_storage_Memory'                 => 'phar://Wotsit/src/wotsit/storage/Memory.php',
        'wotsit_storage_MongoDb'                => 'phar://Wotsit/src/wotsit/storage/MongoDb.php',
        'wotsit_storage_PersistentMemory'       => 'phar://Wotsit/src/wotsit/storage/PersistentMemory.php',
        'wotsit_storage_Sqlite'                 => 'phar://Wotsit/src/wotsit/storage/Sqlite.php',
        'wotsit_Classifier'                     => 'phar://Wotsit/src/wotsit/Classifier.php',
        'wotsit_Feature'                        => 'phar://Wotsit/src/wotsit/Feature.php',
    	'wotsit_iClassifier'                    => 'phar://Wotsit/src/wotsit/iClassifier.php',
        'wotsit_iExtractFeatures'               => 'phar://Wotsit/src/wotsit/iExtractFeatures.php',
        'wotsit_iStorage'                       => 'phar://Wotsit/src/wotsit/iStorage.php',
    );

    /**
     * class loader
     *
     * @param string $className
     * @return boolean
     */
    public static function load($className)
    {
        $isLoaded = false;
        if (isset(self::$classes[$className])) {
            include self::$classes[$className];
            $isLoaded = true;
        }
        return $isLoaded;
    }

}

/**
 * Cli runner
 */
class CliRunner
{

    /**
     * @param array $argv
     * @return void
     */
    public function processParameters(array $argv)
    {
        $filename = $argv[0];
        $switches = array();
        $commands = array();
        foreach (array_slice($argv, 1) as $arg) {
            if (substr($arg, 0, 2) == '--') {
                $switches[] = $arg;
            } else {
                $commands[] = $arg;
            }
        }

        switch (true) {
        case in_array('test', $commands):
            require_once 'PHPUnit/Autoload.php';
            $verbose = in_array('--verbose', $switches);
            $this->printHeader($filename);
            if (in_array('--testdox', $switches)) {
                $listener = new PHPUnit_Util_TestDox_ResultPrinter_Text();
            } else {
                $listener = new PHPUnit_TextUI_ResultPrinter(null, $verbose);
            }
            $this->runTests($listener);
            break;
        case in_array('list', $commands):
            $this->printHeader($filename);
            $this->listContent($filename);
            break;
        case in_array('--help', $switches):
        case in_array('help', $commands):
        default:
            $this->printHeader($filename);
            $this->printHelp();
            break;
        }
    }

    /**
     * @param PHPUnit_Framework_TestListener $listener
     * @return void
     */
    private function runTests(PHPUnit_Framework_TestListener $listener)
    {
        echo "RUNNING TEST SUITE:\n^^^^^^^^^^^^^^^^^^^\n";
        set_include_path(get_include_path() . PATH_SEPARATOR . 'Wotsit.phar');
        require_once 'phar://Wotsit/tests/unit-tests/UnitTests.php';
        $suite = UnitTests::suite();
        $result = new PHPUnit_Framework_TestResult;
        $result->addListener($listener);
        $suite->run($result);
        echo "\n";
        die((int)$result->wasSuccessful());
    }

    /**
     * @param string $filename
     * @return void
     */
    private function listContent($filename)
    {
        $p = new Phar($filename, 0);
        echo "LISTING METADATA\n^^^^^^^^^^^^^^^^\n";
        foreach ($p->getMetadata() as $key => $value) {
            echo "\t{$key}: {$value}\n";
        }

        echo "\nLISTING CONTENTS\n^^^^^^^^^^^^^^^^\n";
        foreach (new RecursiveIteratorIterator($p) as $file) {
            $path = $file->getPathname();
            $path = substr($path, strpos($path, $filename) + strlen($filename));
            echo "\t{$path}\n";
        }
    }

    /**
     * @param string $filename
     * @return void
     */
    private function printHeader($filename)
    {
        $p = new Phar($filename, 0);
        
        $meta = $p->getMetadata();
        echo <<<EOD
{$meta['Title']} by {$meta['Author']}

EOD;

    }

    /**
     * @return void
     */
    private function printHelp()
    {     
        echo <<<EOD
Usage: php Wotsit.phar [switches] test
       php Wotsit.phar [switches] help

  --verbose         Will output a more verbose test report


EOD;
    die();
    }

}

__HALT_COMPILER();