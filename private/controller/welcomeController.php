<?php
include 'baseController.php';

class WelcomeController extends BaseController {

    public function home($name=null) {
        if(!isset($name)) {
            $m = $this->model('WelcomeModel');
            $name = $m->selectName();
        }
        $this->view('WelcomeView', 'Hello '.$name);
    }

}
?>