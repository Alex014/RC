<?php

define('SQL_TABLE_LEFT', '`');
define('SQL_TABLE_RIGHT', '`');
define('SQL_FIELD_LEFT', '`');
define('SQL_FIELD_RIGHT', '`');
define('SQL_VALUE_LEFT', "'");
define('SQL_VALUE_RIGHT', "'");

/**
 * Standart Database class
 * uses PhpDataObjects
 *
 * @author user
 */
class db {
  
   public static $connection;
   public static $params;
   public static $driver = 'mysql';
   public static $session;
  
   /**
    * Connect to database
    * 
    * @param type $params
    * $params['connectionString'] - PDO connection string
    * $params['username']
    * $params['password']
    * $params['charset']
    * $params['session_table']
    * @return type 
    */
  public static function connect($params) {
    self::$params = $params;
    self::$connection = new PDO(self::$params['connectionString'], self::$params['username'], self::$params['password']);
    self::$connection->query('SET NAMES "'.self::$params['charset'].'"');
    if(self::$connection) return true;
  }
  
  public function __destruct() {
    unset(self::$connection);
  }
  
  /**
   * Start database session using table $params['session_table']
   */
  public static function start_session() {
    $session = new session(self::$connection, self::$params['session_table']);
    self::$session = $session;
    //session_start();    
  }

  private static function show_db_error($sql = '') {
    $err = self::$connection->errorInfo();
    if(($err[1] != '1115') && ($err[1] != '')) {
      $info = implode("<br/>", $err);
      $info = "<div><b>Database ERROR:</b> $info</div>";
      if($sql != '')
        $info .= "IN <div><code>$sql</code></div>";
      throw new dbException($info);
    }
  }

  /**
   * Execute Query
   * 
   * @param type $sql
   * @return type 
   */
  public static function execQuery($sql) {
    $res =  self::$connection->exec($sql);
    if(!$res) self::show_db_error($sql);
    return $res;
  }

  public static function escapeValue($value) {
    $res =  self::$connection->quote($value);
    if(!$res) self::show_db_error();
    return $res;
  }

  /**
   * Return query result as an array
   * 
   * @global <PDO connection> $this->db_connection()
   * @param <string> $sql
   * @return <array>
   */
  public static function getQuery($sql) {
    
      $result = array();

      $st = self::$connection->query($sql);
      if($st) {
        $st->setFetchMode(PDO::FETCH_NAMED);
        while ($row = $st->fetch())
          $result[] = $row;
        return $result;
      }
      else {
        self::show_db_error($sql);
        return false;
      }
  }  
  
  /**
   * Return query result as an array
   * 
   * @global <type> $this->db_connection()
   * @param <string> $sql
   * @param <bool> $zero_index
   * @return <array>
   */
  public static function getList($sql, $zero_index = true) {
    
      $result = array();

      $st = self::$connection->query($sql);
      if($st) {
        $st->setFetchMode(PDO::FETCH_NUM);
        if($zero_index)
          $result[0] = '';
        while ($row = $st->fetch())
          $result[$row[0]] = $row[1];
        return $result;
      }
      else {
        self::show_db_error($sql);
        return false;
      }
  } 

  /**
   * Return query first row as an array
   * 
   * @global  $this->db_connection()
   * @param <type> $sql
   * @return <type>
   */
  public static function getResult($sql) {
    
      $result = array();
      $st = self::$connection->query($sql);
      if($st) {
        $st->setFetchMode(PDO::FETCH_NAMED);
        while ($row = $st->fetch())
          return $row;
      }
      else {
        self::show_db_error($sql);
        return false;
      }
  }

  /**
   * Return query result first column value
   * 
   * @global  $this->db_connection()
   * @param <type> $sql
   * @return <type>
   */
  public static function getValue($sql) {
    
    $st = self::$connection->prepare($sql);
    if($st) {
      $st->execute();
      $result = $st->fetchColumn(0);
      return $result;
    }
      else {
        self::show_db_error($sql);
        return false;
      }
  }

  /**
   * Last inserted ID from table
   * @param type $table
   * @return type 
   */
  public static function getLastId($table) {
    
    $res = self::$connection->lastInsertId($table);
    if(!$res) self::show_db_error();
    return $res;
  }
  
  /**
   * SQL limit code
   * 
   * @param int $from
   * @param int $offset
   * @return string 
   */
  public static function sqlLimit($from, $offset = '') {
    if($offset != '')
      return "LIMIT $from, $offset";
    else
      return "LIMIT $from";
  }
  
  /**
   * SQL CurrentDate code
   * @return string 
   */
  public static function sqlCurrentDate() {
    return "CURRENT_DATE()";
  }
  
  /**
   * SQL CurrentDateTime code
   * @return string 
   */
  public static function sqlCurrentDateTime() {
    return "NOW()";
  }
  
  /**
   * SQL UNIX TIMESTAMP code
   * @return string 
   */
  public static function sqlTimestamp() {
    return "UNIX_TIMESTAMP()";
  }
  
}
  
?>
