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

//change working directory
chdir(dirname(__FILE__));

/**
 * Autoloader for classes depending on filename.
 * 
 * @param string $pClassName Name of class/file.
 * @return boolean
 */
function __autoload($pClassName) {
    if (file_exists($pClassName.'.php')) {
        require_once($pClassName.'.php');
        return true;
    } else if (file_exists('wrapper/'.$pClassName.'.php')) {
        require_once('wrapper/'.$pClassName.'.php');
        return true;
    }
    return false; 
}

/**
 * DataIntegrator class.
 *
 * Base class for data integration library. Initiates data integration process.
 *
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class DataIntegrator {

    private $data;

    /**
     * Creates a new mediator and requests data from it.
     * 
     * @return DomDocument
     */
    public function getData() {
        //create new mediator instance
        $Mediator = new Mediator();

        //get datagraph from mediator
        $this->data = $Mediator->getData();

        return $this->data;
    }

}

?>