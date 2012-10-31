<?php

/**
 * Exceptions
 */

/**
 * Standart exception
 */
class rc_Exception extends Exception {

    public function __construct($errno, $errstr, $errfile, $errline) {
        parent::__construct($errstr, $errno);
        $this->code = $errno;
        $this->message = $errstr;
        $this->file = $errfile;
        $this->line = $errline;
    }
  
    function __toString() {
        output::$return = true;
        output::$delimiter = ":";
        output::$show_index = false;
        $trace = output::show_array($this->getTrace());
        return <<<ERR
 <center>
   <table>
    <tr>
      <th colspan='2'>{$this->getMessage()}</th>
    </tr>
    <tr>
      <td>Code:</td>
      <td>{$this->getCode()}</td>
    </tr>
    <tr>
      <td>File:</td>
      <td>{$this->getFile()}</td>
    </tr>
    <tr>
      <td>Line:</td>
      <td>{$this->getLine()}</td>
    </tr>
    <tr>
      <td>Trace:</td>
      <td>{$trace}</td>
    </tr>
   </table>
 </center>
ERR;
    }
}

function err2exc($errno, $errstr, $errfile, $errline) {
    throw new rc_Exception($errno, $errstr, $errfile, $errline);
}

set_error_handler('err2exc', E_ALL & E_NOTICE & E_USER_NOTICE & E_STRICT);


/**
 * Custom exception
 */
class rcException extends Exception {

    public function __construct($errstr, $errno = 0) {
        parent::__construct($errstr, $errno);
        $this->code = $errno;
        $this->message = $errstr;
    }
  
    function __toString() {
        output::$return = true;
        output::$delimiter = ":";
        output::$show_index = false;
        $trace = output::show_array($this->getTrace());
        return <<<SQL
 <center>
   <table>
    <tr>
      <th colspan='2'>{$this->getMessage()}</th>
    </tr>
    <tr>
      <td>Code:</td>
      <td>{$this->getCode()}</td>
    </tr>
    <tr>
      <td>File:</td>
      <td>{$this->getFile()}</td>
    </tr>
    <tr>
      <td>Line:</td>
      <td>{$this->getLine()}</td>
    </tr>
    <tr>
      <td>Trace:</td>
      <td>{$trace}</td>
    </tr>
   </table>
 </center>
SQL;
    }
}

/**
 * DB exception with SQL output
 */
class dbException extends rcException {
    public function __construct($errstr, $sql, $errno = 0) {
        output::$return = true;
        $errstr = "DB error: $errstr <br/> ".
                "<div style='text-align: left; font-weight: normal; margin-left: 20px;'>".
                output::show_sql($sql)."</div>";
        parent::__construct($errstr, $errno);
        $this->code = $errno;
        $this->message = $errstr;
    }
}

/**
 * Routing exception
 */
class routingException extends rc_Exception {
    public function __construct($errstr) {
        $code = E_PARSE;
        $message = $errstr;
        $file = dirname(__FILE__)."/../config/routes.php";
        $line = 1;
        parent::__construct($code, $message, $file, $line);
    }
}