<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of base_model
 *
 * @author user
 */
class base_model {
  public $pk = 'id';
  public $table = '';
  
  public function __construct() {
    $this->table = get_config("tables.$this->table");
  }
  
  public function table($table) {
    return SQL_TABLE_LEFT.get_config("tables.".$table).SQL_TABLE_RIGHT;
  }
  
  public function __call($method_name, $arguments) {
    $parts = explode("_", $method_name);
    //Check for errors
    $descr_select = "<br/><br/>model::select_[condition:(c;pk)]_[result output:(a;l;r;v)](<operation params>, [condition params], [order_by], [limit_from], [limit_offset])";
    $descr_update = "<br/><br/>model::update_[condition:(c;pk)](<operation params>, <condition params>)";
    $descr_insert = "<br/><br/>model::insert_(<operation params>)";
    $descr_delete = "<br/><br/>model::delete(<condition params>)";
    $descr = <<<DDD
    <p></p>
    <p>------------------condition-------------------</p>
    <p><b>c or condition</b> - array or string</p>
    <p>-----------------result output----------------</p>
    <p><b>a or array</b> - result as array of rows</p>
    <p><b>l or list</b> - result of array(field1 => field2, ...)</p>
    <p><b>r or row</b> - single row result</p>
    <p><b>v or value</b> - single value result</p>
    <p>-------------------Params---------------------</p>
    <p><operation params>: string or array</p>
    <p><condition params>: string or array</p>
    <p><order_by>: string</p>
    <p><limit_from>: integer</p>
    <p><limit_offset>: integer</p>
DDD;
    switch($parts[0]) {
      case 'sql': 
        if(isset($parts[1])) {
          $method_name = "sql".$parts[1];
          return call_user_func_array(array(db, $method_name), $arguments);
        }
      break;
      case 'select':
        //Parts
        if(isset($parts[1])) {
          $part_condition = $parts[1];
          if(isset($parts[2])) {
            $part_result = $parts[2];
          }
          else {
            $part_result = 'a';
          }
        }
        else {
          $part_condition = "c";
        }
        //Arguments
        if(isset($arguments[0])) {
          $param_operation = $arguments[0];
          if(isset($arguments[1])) {
            $param_condition = $arguments[1];
            if(isset($arguments[2])) {
              $param_order_by = $arguments[2];
              if(isset($arguments[3])) {
                $param_limit_from = $arguments[3];
                if(isset($arguments[4])) {
                  $param_limit_offset = $arguments[4];
                }
                else {
                  $param_limit_offset = "";
                }
              }
              else {
                $param_limit_from = "";
              }
            }
            else {
              $param_order_by = "";
            }
          }
          else {
            $param_condition = "1";
          }
        }
        else {
          throw new Exception("No <operation params>: ".$descr_select.$descr);
        }
        
        $sql = call_user_func(array($this, '_select'), $param_operation);
        $sql .= call_user_func(array($this, "condition_".$part_condition), $param_condition);
        if($param_order_by != '')
          $sql .= " ORDER BY $param_order_by ";
        if($param_limit_from != '')
          $sql .= " ".db::sqlLimit($param_limit_from, $param_limit_offset);
        //print $sql;
        return call_user_func(array($this, "result_".$part_result), $sql);
      break;
      case 'update':
        //Parts
        if(isset($parts[1])) {
          $part_condition = $parts[1];
        }
        else {
          $part_condition = "c";
        }
        //Arguments
        if(isset($arguments[0])) {
          $param_operation = $arguments[0];
          if(isset($arguments[1])) {
            $param_condition = $arguments[1];
          }
          else {
            throw new Exception("No <operation params>: ".$descr_update.$descr);
          }
        }
        else {
          throw new Exception("No <condition params>: ".$descr_update.$descr);
        }
        
        $sql = call_user_func(array($this, '_update'), $param_operation);
        $sql .= call_user_func(array($this, "condition_".$part_condition), $param_condition);
        //print $sql;
        return call_user_func(array($this, "result_exec"), $sql);
      break;
      case 'insert':
        //Parts
        // ...
        //Arguments
        if(isset($arguments[0])) {
          $param_operation = $arguments[0];
        }
        else {
          throw new Exception("No <operation params>: ".$descr_insert.$descr);
        }
        
        $sql = call_user_func(array($this, '_insert'), $param_operation);
        //print $sql;
        return call_user_func(array($this, "result_exec"), $sql);
      break;
      case 'delete':
        //Parts
        if(isset($parts[1])) {
          $part_condition = $parts[1];
        }
        else {
          $part_condition = "c";
        }
        //Arguments
        if(isset($arguments[0])) {
          $param_condition = $arguments[0];
        }
        else {
          throw new Exception("No <condition params>: ".$descr_update.$descr);
        }
        
        $sql = call_user_func(array($this, '_delete'), array());
        $sql .= call_user_func(array($this, "condition_".$part_condition), $param_condition);
        //print $sql;
        return call_user_func(array($this, "result_exec"), $sql);
      break;
    }
    /*
    //Arguments
    if(count($arguments) == 1) {
      $operation_params = $arguments[0];
      $condition_params = '';
    }
    elseif(count($arguments) == 2) {
      $operation_params = $arguments[0];
      $condition_params = $arguments[1];
    }
    //processing parts and params
    for($i = 0; $i < count($parts); $i++) {
      if($i == 0) {
        $sql = call_user_func(array($this, '_'.$parts[$i]), $operation_params);
        //print $sql;
      }
      elseif($i == 1) {
        $condition_suffix = $parts[$i];
        if($condition_suffix == '') $condition_suffix = 'c';
        if($condition_params == '') $condition_params = '1';
        
        $sql .= call_user_func(array($this, "condition_".$condition_suffix), $condition_params);
      }
      elseif($i == 2) {
        $result_output = $parts[$i];
        if($result_output == '') $result_output = 'a';
        //print $sql;
        return call_user_func(array($this, "result_".$result_output), $sql);
      }
    }
    
    if(count($parts)  == 1) {
      //Pk condition by default
      if($condition_params != '')
        $sql .= " ".call_user_func(array($this, "condition_c"), $condition_params);
      //print $sql;
    }
    
    if(count($parts) < 3) {
      //Executing by default
      //print $sql;
      return call_user_func(array($this, "result_exec"), $sql);
    }*/
    
  }

    /**
     * Make value-list string for select, update, etc
     * @param <type> $params
     * @param <type> $eq__word ussualy '=' (where, update)
     * @param <type> $and_word usualy 'AND' (where) ',' (update)
     * @return <type>
     */
    private function _make_values($params, $eq__word = ' = ', $and_word = ' AND ') {
      $values = array();
      foreach($params as $field => $value) {
        $value = db::escapeValue($value);
        $values[] = SQL_FIELD_LEFT.$field.SQL_FIELD_RIGHT .
                $eq__word. 
                $value;
      }
      $values = implode($and_word, $values);
      return $values;
    }
  
  protected function _insert($fields_values) {
      foreach($fields_values as $field => $value) {
        $_fields[] = SQL_FIELD_LEFT.$field.SQL_FIELD_RIGHT;
        $_values[] = db::escapeValue($value);
      }
      $_fields = implode(',', $_fields);
      $_values = implode(',', $_values);

      $sql = "INSERT INTO ".SQL_TABLE_LEFT."$this->table".SQL_TABLE_RIGHT." ($_fields) VALUES ($_values)";
      return $sql;
  }
  
  protected function _select($fields = '*') {
    if(is_array($fields)) {
      foreach($fields as $field) 
        $sel_fileds[] = SQL_FIELD_LEFT.$fields.SQL_FIELD_RIGHT;
      $sel_fileds = implode(',', $sel_fields);
    }
    else {
      $sel_fields = $fields;
    }
    $sql = "SELECT $sel_fields FROM ".SQL_TABLE_LEFT."$this->table".SQL_TABLE_RIGHT."";
    return $sql;
  }
  
  protected function _update($fields_values) {
      $_values = $this->_make_values($fields_values,'=',',');
      $sql = "UPDATE ".SQL_TABLE_LEFT."$this->table".SQL_TABLE_RIGHT." SET $_values";
      return $sql;
  }
  
  private function query($sql) {
    return $sql;
  }
  
  protected function _del() {
    return $this->_delete();
  }
  
  protected function _delete() {
    //$_values = $this->_make_values($fields_values,'=',' AND ');
    $sql = "DELETE FROM ".SQL_TABLE_LEFT."$this->table".SQL_TABLE_RIGHT;
    return $sql;
  }
  
  protected function condition_pk($pk_value) {
    $sql = "WHERE $this->pk = $pk_value";
    return $sql;
  }
  
  protected function condition_c($condition) {
    return $this->condition_condition($condition);
  }
  
  protected function condition_condition($condition) {
    if(is_string($condition)) {
      $sql = " WHERE ".$condition;
      return $sql;      
    }
    elseif(is_array($condition)) {
      if(count($condition) == 0)
        return " WHERE 1";
      foreach($condition as $field => $value) {
        $value = db::escapeValue($value);
        $where[] = "`$field` = ".$value;
      }
      $sql = implode(' AND ', $where);
      return " WHERE ".$sql;      
    }
    else {
      throw new Exception("<div>The condition parameter must be string or array</div>");
    }
  }
  
  private function result_exec($sql) {
    return db::execQuery($sql);
  }
  
  private function result_a($sql) {
    return $this->result_array($sql);
  }
  
  private function result_array($sql) {
    return db::getQuery($sql);
  }
  
  private function result_l($sql) {
    return $this->result_list($sql);
  }
  
  private function result_list($sql) {
    return db::getList($sql);
  }
  
  private function result_r($sql) {
    return $this->result_row($sql);
  }
  
  private function result_row($sql) {
    return db::getResult($sql);
  }
  
  private function result_v($sql) {
    return $this->result_value($sql);
  }
  
  private function result_value($sql) {
    return db::getValue($sql);
  }
  

  function get_last_id() {
    return db::getLastId($this->table);
  }
}

?>
