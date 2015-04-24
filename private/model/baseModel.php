<?php

class BaseModel {

    public $db;
    
    public function __construct() {
        $this->db = new mysqli(DBHOST, DBUSER, DBPASS, DATABASE);
    }

    public function insert($sql) {
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $statement->close();
    }

    public function select($sql) {
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $statement->store_result();
        $statement->bind_result($result);
        $statement->fetch();
        $statement->close();
        return $result;
    }

}

?>