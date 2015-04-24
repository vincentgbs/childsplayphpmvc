<?php

class BaseController {

    public function model($model) {
        include LOCATION . 'private/model/'. $model . '.php';
        return new $model();
    }

    public function view($view, $data=null) {
        include LOCATION . 'private/view/'. $view . '.php';
    }

    public function GET($name=NULL, $value=false, $option=false) {
        $content=(!empty($_GET[$name]) ? trim($_GET[$name]) : (!empty($value) && !is_array($value) ? trim($value) : false));
        if(is_numeric($content))
            return preg_replace("@([^0-9])@Ui", "", $content);
        else if(is_bool($content))
            return ($content?true:false);
        else if(is_float($content))
            return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
        else if(is_string($content))
        {
            if(filter_var ($content, FILTER_VALIDATE_URL))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_EMAIL))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_IP))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_FLOAT))
                return $content;
            else
                return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
        }
        else false;
    }

    public function POST($name=NULL, $value=false, $option=false) {
        $content=(!empty($_POST[$name]) ? trim($_POST[$name]) : (!empty($value) && !is_array($value) ? trim($value) : false));
        if(is_numeric($content))
            return preg_replace("@([^0-9])@Ui", "", $content);
        else if(is_bool($content))
            return ($content?true:false);
        else if(is_float($content))
            return preg_replace("@([^0-9\,\.\+\-])@Ui", "", $content);
        else if(is_string($content))
        {
            if(filter_var ($content, FILTER_VALIDATE_URL))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_EMAIL))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_IP))
                return $content;
            else if(filter_var ($content, FILTER_VALIDATE_FLOAT))
                return $content;
            else
                return preg_replace("@([^a-zA-Z0-9\+\-\_\*\@\$\!\;\.\?\#\:\=\%\/\ ]+)@Ui", "", $content);
        }
        else false;
    }

}

?>