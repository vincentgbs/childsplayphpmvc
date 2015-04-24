<?php
include 'BaseModel.php';

class UserModel extends BaseModel {

    public function uniqueActivation() {
        do {$activate = uniqid() . uniqid() . uniqid() . uniqid(); // semi-random string
            $sql = "SELECT * FROM temporary WHERE activate = '" . $activate ."';";
            $row = $this->selectRow($sql);
        } while(count($row) != 0);
        return $activate;
    }

    public function emailTaken($email) {
        return ($this->checkExisting("users", "email", $email) 
        || $this->checkExisting("temporary_users", "email", $email));
    }

    public function usernameTaken($username) {
        return ($this->checkExisting("users", "username", $username) 
        || $this->checkExisting("temporary_users", "username", $username));
    }

    public function insertTemporary($username, $email, $password, $random_salt, $activate) {
        $sql = "INSERT INTO `temporary_users`
        (`username`, `email`, `password`, `salt`, `activate`)
        VALUES ('$username', '$email', '$password', '$random_salt', '$activate')";
        $this->insert($sql);
    }

    public function checkActivate($activate) {
        $sql = "SELECT `username`, `email`, `password`, `salt` 
            FROM `temporary_users` WHERE `activate` = '$activate'";
        $row = $this->selectRow($sql);
        if (count($row) == 0) { return false; }
        return $row;
    }

    public function insertVerified($data) {
        $sql = "INSERT INTO `users` (`username`, `email`, `password`, `salt`)
        VALUES ('".$data['username']."', '".$data['email'].
            "', '".$data['password']."', '".$data['salt']."')";
        $this->insert($sql);
    }

    public function deleteTemporary($data) {
        $sql = "DELETE FROM `temporary_users`
        WHERE `username`='".$data['username']."'
        AND `email`='".$data['email']."'";
        $this->execute($sql);
    }

    public function checkLogin($userId=null, $email=null) {
        $sql = "SELECT * FROM `users`";
        if(isset($userId)) {
            $sql .= " WHERE `user_id` = '$userId' ";
        } else if (isset($email)) {
            $sql .= " WHERE `email` = '$email' ";
        }
        $sql .=  "LIMIT 1";
        return $this->selectRow($sql);
    }

    public function loginAttempt($userId, $now, $success) {
        $sql = "INSERT INTO login_attempts(user_id, time, success)
            VALUES ('$userId', '$now', '$success')";
        $this->insert($sql);
        }

    public function checkbrute($now, $userId, $timeLimit=360) {
        $valid_attempts = $now - $timeLimit;

        $sql = "SELECT COUNT(`time`) AS attempt FROM `login_attempts` 
                WHERE `user_id`='$userId' AND `success`='0' 
                AND `time` > '$valid_attempts'";
        return $this->selectOne($sql);
        }

}

?>