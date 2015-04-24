<?php

class BaseModel {

    public $db;
    
    public function __construct() {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DATABASE);
    }

    public function insert($sql) {
        $statement = $this->db->prepare($sql);
        if($statement == false) { return false; }
        $statement->execute();
        $statement->close();
        return true;
    }

    public function execute($sql) {
        return $this->insert($sql);
    }

    public function selectOne($sql) {
        $statement = $this->db->prepare($sql);
        if($statement == false) { return; }
        $statement->execute();
        $statement->store_result();
        $statement->bind_result($result);
        $statement->fetch();
        $statement->close();
        return $result;
    }

    public function selectRow($sql) {
        $result = $this->db->query($sql);
        if($result == false) { return; }
        return $result->fetch_array(MYSQLI_ASSOC);
    }

//    public function selectAll($sql){
//        $result = $this->db->query($sql);
//        if($result == false) { return; }
//        mysqli_fetch_all($result, MYSQLI_ASSOC);
//        $return = array();
//        foreach($result as $row) { $return[] = $row; }
//        return $return;
//    }

    public function checkExisting($tableName, $column, $param) {
        $query = "SELECT * FROM `".$tableName."` WHERE `".$column."` = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param('s', $param);
        $statement->execute();
        $statement->store_result();
        $row_count = $statement->num_rows;
        $statement->close();
        if ($row_count >= 1) { return true; }
        else { return false; }  
    }

}

?>