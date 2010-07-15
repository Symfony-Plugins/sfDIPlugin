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
 * Wrapper class for Flickr API.
 *
 * Requests data from Flickr using 3rd party API. Stores response in dataobject
 * extending datagraph.
 *
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class WrapperFlickr extends Wrapper {

    private $config;
    private $data;
    private $rawData;

    /**
     * Stores given config from calling class in member variable.
     */
    public function __construct($pConfig) {
        parent::__construct();
        $this->config = $pConfig;
    }

    /**
     * Requests data from data source and stores response as xml string.
     */
    public function getData() {
        //prepare parameters for data source request
        $tParams = array(
            'api_key'   => $this->config['Flickr']['api_key'],
            'method'    => $this->config['Flickr']['method'],
            'user_id'   => $this->config['Flickr']['user_id'],
        );
        $tEncodedParams = array();

        //URL encode parameters
        foreach ($tParams as $k => $v) {
            $tEncodedParams[] = urlencode($k).'='.urlencode($v);
        }

        //request data source
        $tUri = $this->config['Flickr']['uri']."?".implode('&', $tEncodedParams);
        $this->rawData = file_get_contents($tUri);

        //store data source response as xml string
        $this->data = simplexml_load_string($this->rawData);
        if (!$this->data) {
            trigger_error("Failed to get data", E_USER_ERROR);
        }
    }

    /**
     * Maps data from data source response in dataobject extending datagraph. 
     */
    public function mapData() {
        //check response status...
        if (
            $this->data['stat'] != "ok" ||
            !is_object($this->data->photos->photo)
        ) {
            trigger_error("Failed to map data", E_USER_ERROR);
            return;
        }

        //...and iterate through elements if valid
        foreach($this->data->photos->photo as $tVal) {
            //skip empty elements
            if (empty($tVal)) {
                continue;
            }

            //add dataobjects as entities/attributes to datagraph
            $this->DataGraph->push(
                //setup array with key/value pairs as entities/attributes
                array(
                    "node" => array (
                        "title" => $tVal['title'],
                        "thumb" => 
                                "http://".
                                $this->config['Flickr']['uri_farm'].
                                $tVal['farm'].
                                ".".
                                $this->config['Flickr']['uri_server'].
                                $tVal['server'].
                                "/".
                                $tVal['id'].
                                "_".
                                $tVal['secret'].
                                "_t.jpg",
                        "uri" =>
                                "http://".
                                $this->config['Flickr']['uri_farm'].
                                $tVal['farm'].
                                ".".
                                $this->config['Flickr']['uri_server'].
                                $tVal['server'].
                                "/".
                                $tVal['id'].
                                "_".
                                $tVal['secret'].
                                "_b.jpg",
                    )
                )
            );
        }
    }

}

?>