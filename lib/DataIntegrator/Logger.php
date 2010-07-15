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
 * Logger class.
 *
 * Overwrites PHP error handler with user defined one. Writes log messages to a
 * specified log file.

 * @package    DataIntegrator
 * @author     Tommy Schmidt <schmidto@fh-brandenburg.de>
 * @version    1.0
 */
class Logger {

    private $errorLog = false;

    /**
     * Sets log file destination when given. Overwrites PHP error handler.
     * 
     * @param boolean $pLogging Determines if a log file should be written.
     * @param string $pErrorLog Error log file to store log messages in.
     */
    public function __construct($pLogging, $pErrorLog = false) {
        if ($pLogging) {
            //set target log file
            $this->errorLog = $pErrorLog;
        }
        //set user defined error handler
        set_error_handler(array(&$this, 'userErrorHandler'));
    }

    /**
     * Creates user error handler.
     * 
     * @param string|int $pErrNo Error key.
     * @param string $pErrMsg Error message.
     * @param string $pFilename Filename of error causing script.
     * @param int $pLineNum Error line number of error causing script.
     * @param mixed $pVars Error involved variables.
     */
    public function userErrorHandler($pErrNo, $pErrMsg, $pFilename, $pLineNum, $pVars) {
        $tErr = '';

        //get date time for error entry
        $tDateTime = date('Y-m-d H:i:s (T)');

        //define error types
        $tArrErrorTypes = array (
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );

        //errors for which a var trace will be saved
        $tUserErrors = array(
            E_ERROR,
            E_WARNING,
            E_NOTICE
        );

        //build error string
        $tErr .= '['.$tDateTime.'] ';
        $tErr .= '['.$pErrNo.'] ';
        $tErr .= '['.$tArrErrorTypes[$pErrNo].'] ';
        $tErr .= 'Message: '.$pErrMsg.' ';
        $tErr .= 'in script: '.$pFilename.' ';
        $tErr .= 'at line: '.$pLineNum.' ';
        if (in_array($pErrNo, $tUserErrors)) {
            $tErr .= 'trace: '.wddx_serialize_value($pVars, 'Variables').'';
        }

        //save error string to error log file if set
        if ($this->errorLog) {
            error_log($tErr."\n\n", 3, $this->errorLog);
        }
    }
}

?>