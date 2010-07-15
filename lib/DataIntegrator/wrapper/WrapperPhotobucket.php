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
 * Wrapper class for Photobucket API.
 *
 * Requests data from Photobucket using 3rd party API. Stores response in dataobject
 * extending datagraph.
 *
 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class WrapperPhotobucket extends Wrapper {

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
        //load third party API
        require_once('PBAPI.php');

        try {
            //create 3rd party API instance and prepare parameters for data
            //source request
            $PBAPI = new PBAPI(
                $this->config['Photobucket']['api_key'],
                $this->config['Photobucket']['secret']
            );

            //set response format
            $PBAPI->setResponseParser('phpserialize');

            //request data source
            $this->rawData = $PBAPI->album($this->config['Photobucket']['user'])->get()->getResponseString();

            //store data source response as object
            $this->data = unserialize($this->rawData);
        } catch (PBAPI_Exception_Response $e) {
            trigger_error('Failed to get data: '.$e, E_USER_ERROR);
        } catch (PBAPI_Exception $e) {
            trigger_error('Failed to get data: '.$e, E_USER_ERROR);
        }
    }
    /**
     * Maps data from data source response in dataobject extending datagraph. 
     */
    public function mapData() {
        //check response status...
        if (
            $this->data['status'] != "OK" ||
            !is_array($this->data['content']['album']['media'])
        ) {
            trigger_error("Failed to map data", E_USER_ERROR);
            return;
        }

        //...and iterate through elements if valid
        foreach($this->data['content']['album']['media'] as $tVal) {
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
                        "thumb" => $tVal['thumb'],
                        "uri" => $tVal['url'],
                    )
                )
            );
        }
    }

}

?>