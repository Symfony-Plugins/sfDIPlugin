<?php
/**
 * DataIntegrator Library.
 * 
 * PHP Library for integrating heterogeneous data from different data sources.
 *
 * PHP version 5
 * 
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 * @filesource
 * 
 * @copyright Copyright (c) 2010, Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Mediator class.
 *
 * Responsible for loading configuration, logger and wrapper. Passes datagraph
 * to invoking class.
 *
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class Mediator {

    private $config;
    private $errorReportingLevel;

    /**
     * Returns false when config could not be loaded. Initiates logger and
     * wrapper functionality. Overwrites current error reporting level.
     * 
     * @return boolean
     */
    public function __construct() {
        //load config
        if (!$this->config = $this->loadConfig()) {
            return false;
        }

        //get current error reporting level
        $this->errorReportingLevel = error_reporting();

        //overwrite current error reporting level
        error_reporting(E_ERROR | E_PARSE);

        //load logger
        $this->loadLogger();

        //get and load wrapper
        $this->loadWrapper();

        return true;
    }

    /**
     * Resets to original error reporting level.
     */
    public function __destruct() {
        error_reporting($this->errorReportingLevel);
    }

    /**
     * Returns datagraph instance.
     */
    public function getData() {
        $DataGraph = DataGraph::instance();
        return $DataGraph->getDataGraph();
    }

    /**
     * Loads and parses config file to member variable.
     */
    private function loadConfig() {
        return parse_ini_file("conf/config.ini", true);
    }

    /**
     * Creates Logger with configuration parameters.
     */
    private function loadLogger() {
        $Logger = new Logger(
            $this->config['logging'],
            $this->config['errorLog']
        );
    }

    /**
     * Searchs for existing wrappers in defined directory and tries to load
     * them. Calls according functions in wrapper for getting and mapping
     * data from datasources.
     */
    private function loadWrapper() {
        //list all files in defined directory
        $tWrapperDirContent = scandir('wrapper/');

        //iterate through directory content
        foreach($tWrapperDirContent as $tVal) {

            //search for files with wrapper prefix
            if (substr($tVal, 0, 7) != 'Wrapper') {
                continue;
            }

            //get wrapper class name
            $tClassName = explode('.', $tVal);
            $tClassName = $tClassName[0];

            //create instance of wrapper class and try to load functions
            //for getting and mapping data
            try {
                $Object = new $tClassName($this->config);
                $Object->getData();
                $Object->mapData();
            } catch (Exception $e) { 
                trigger_error('Failed to load wrapper: '.$tVal, E_USER_ERROR);
            }
        }
    }

}

?>