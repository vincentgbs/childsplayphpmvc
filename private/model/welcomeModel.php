<?php
include 'BaseModel.php';

class WelcomeModel extends BaseModel {

    public function selectName() {
        return $this->select('SELECT `name` FROM `default_name_table`');
    }

}

?>