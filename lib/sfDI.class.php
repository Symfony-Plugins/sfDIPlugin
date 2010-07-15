<?php

/**
 * sfDI class.
 *
 * Creates DataIntegrator instance and initiates data retrieving process.
 * Transforms response data to SimpleXMLElement.
 *
 * @package    sfDIPlugin
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 */

/**
 * Plugin
 */

class sfDI
{

    /**
     * Loads and starts data integration process. Returns response as
     * SimpleXMLElement.
     * 
     * @return SimpleXMLElement
     */
    public static function getContent()
    {
        require_once('DataIntegrator/DataIntegrator.php');
        $DataIntegrator = new DataIntegrator();
        return simplexml_import_dom($DataIntegrator->getData());
    }
	
	/**
	 * Get base URL dir for plugin.
	 *
	 * @return String
	 */
	protected static function getBaseDir()
	{
		return public_path('sfDIPlugin/');
	}
}