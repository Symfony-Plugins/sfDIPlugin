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
 * DataGraph class (singleton).
 *
 * Creates, extends and returns datagraph. Uses singleton pattern to reflect as
 * sole instance for several wrapper.
 *
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class DataGraph {

    private $dataGraph;
    private $dataGraphRoot;
    private static $instance = NULL;

    /**
     * Private access modifier assures that constructor can only be called by
     * this class itself (singleton). Avoids several instances of this class.
     */
    private function __construct() {
        $this->createDataGraph();
    }

    /**
     * Private access modifier avoids object cloning.
     */
    private function __clone() {
    }

    /**
     * Creates an instance of this class if none exists. Returns instance
     * otherwise.
     * 
     * @return DataGraph
     */
    public static function instance() {
        if(self::$instance === NULL) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Creates an empty datagraph as DomDocument with root node.
     */
    private function createDataGraph() {
        //create DOMDocument
        $this->dataGraph = new DomDocument('1.0', 'UTF-8');

        //create and set root node
        $this->dataGraphRoot = $this->dataGraph->createElement('root');
        $this->dataGraph->appendChild($this->dataGraphRoot);
    }

    /**
     * Adds dataobjects (entities/attributes) to the datagraph (DomDocument)
     * by calling itself recursively depending on the depth of the given
     * dataobject array.
     * 
     * @param array $pArr Array of dataobjects.
     * @param DomElement $pElement as entry point for new entities/attributes.
     */
    public function push($pArr, $pElement = false) {
        //determine root node
        $tCurElement = ($pElement) ? $pElement : $this->dataGraphRoot;

        //iterate through given array
        foreach($pArr as $tKey => $tVal) {

            //if sub array is found...
            if (is_array($tVal)) {

                //...create new element
                $tElement = $this->dataGraph->createElement($tKey);

                //append element to DomDocument
                $tCurElement->appendChild($tElement);

                //call this function again with current array position
                $this->push($tVal, $tElement);

            //if an entry point (DomElement) is given...
            } else if ($pElement) {

                //...create attribute and append it their
                $tAttr = $this->dataGraph->createAttribute($tKey);
                $tCurElement->appendChild($tAttr);

                //set and append attribute text
                $tText = $this->dataGraph->createTextNode($tVal);
                $tAttr->appendChild($tText);
            }
        }
    }

    /**
     * Returns datagraph.
     * 
     * @return DomDocument
     */
    public function getDataGraph() {
        return $this->dataGraph;
    }

}

?>