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
 * Wrapper class.
 *
 * Defines methods for deriving classes. Provides datagraph instance.
 * 
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
abstract class Wrapper {

    protected $DataGraph;

    /**
     * Loads datagraph instance.
     */
    public function __construct() {
        $this->DataGraph = DataGraph::instance();
    }

    /**
     * Method for deriving class to request data from data source.
     */
    protected abstract function getData();

    /**
     * Method for deriving class to map data from data source response to
     * dataobject extending datagraph. 
     */
    protected abstract function mapData();

}

?>