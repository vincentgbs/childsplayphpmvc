<?php
include 'BaseController.php';
define('BRUTE', 5);

class UserController extends BaseController {

    public function home() {
        if (isset($_GET['e'])) { $e = $this->GET('e'); }
        if($this->check()) {
            $data = ['e' => isset($e)?$e:null,
            'userId'=>$_SESSION['user_id'],
            'user'=>$_SESSION['username']];
            $this->view('WelcomeView', $data);
        }
    }

    public function register() {
        if (isset($_GET['e'])) {
            $e = $this->GET('e');
            $this->view('RegistrationView', ['e'=>$e]);
            return;
        }
        $this->view('RegistrationView');
    }

    public function login() {
        if (isset($_GET['e'])) {
            $e = $this->GET('e');
            $this->view('LoginView', ['e'=>$e]);
            return;
        }
        $this->view('LoginView');
    }

    public function processRegistration() {
        $this->uModel = $this->model('UserModel');
        if(isset($_POST['username'], $_POST['email'], $_POST['p'])) {
            $username = strtolower($this->POST('username'));
            $email = strtolower($this->POST('email'));
            $password = $this->POST('p');

//            IF YOU WANT TO SET SPECIAL REGISTRATION CONDITIONS
//            if(substr($email, -4) != ".edu") {
//                header('Location: ?app=User/Register&e=You%20must%20register%20with%20a%20".edu"%20email.');
//            }

            if($this->uModel->emailTaken($email)) {
                header('Location: ?app=User/Register&e=email%20already%20registered');
                return;
            }

            if($this->uModel->usernameTaken($username)) {
                header('Location: ?app=User/Register&e=username%20already%20exists');
                return;
            }

            $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $password = hash('sha512', $password . $random_salt);
            $activate = $this->uModel->uniqueActivation();

            $this->uModel->insertTemporary($username, $email, $password, $random_salt, $activate);

            $this->introEmail($email, $activate);
        } else { header('Location: ?app=User/Register&e=registration%20error'); }
    }

    public function verify() {
        $this->uModel = $this->model('UserModel');
        if (isset($_GET['act'])) {
            $activate = $this->GET('act');
        } else {
            header('Location: ?app=User/Register&e=activation%20failure');
            return;
        }
        $row = $this->uModel->checkActivate($activate);
        if(!$row) {
            header('Location: ?app=User/Register&e=invalid%20activation%20link');
            return;
        }
        $this->uModel->insertVerified($row);
        $this->uModel->deleteTemporary($row);
        header('Location: ?app=User/Home&e=registration%20successful');
    }

    public function remove() {
        $this->uModel = $this->model('UserModel');
        if (isset($_GET['act'])) {
            $activate = $this->GET('act');
        } else {
            header('Location: ?app=User/Register&e=removal%20failure');
            return;
        }
        $row = $this->uModel->checkActivate($activate);
        if(!$row) {
            header('Location: ?app=User/Register&e=invalid%20removal%20link');
            return;
        }
        $this->uModel->deleteTemporary($row);
        header('Location: ?app=User/Home&e=removal%20successful');
    }

    public function processLogin() {
        $this->uModel = $this->model('UserModel');

        if (isset($_POST['email'], $_POST['p'])) {
            $email = strtolower($this->POST('email'));
            $password = $this->POST('p');

            $row = $this->uModel->checkLogin(null, $email);
            if(!isset($row)) {
                header('Location: ?app=User/Login&e=email%20not%20found');
                return;
            }

            $now = time();
            $userId = $row['user_id'];
            $username = $row['username'];
            $dbPassword = $row['password'];

            $password = hash('sha512', $password . $row['salt']);
            $check = $this->uModel->checkbrute($now, $userId);
            if ($check['attempt'] >= 5) {
                // send email to "unlock locked account"
                header('Location: ?app=User/Login&e=too%20many%20login%20attempts');
                return;
            }
            if ($dbPassword == $password) {
                $userBrowser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $userId = preg_replace("/[^0-9]+/", "", $userId);
                $_SESSION['user_id'] = $userId;
                // XSS protection as we might print this value
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', 
                          $password . $userBrowser);
                $this->uModel->loginAttempt($userId, $now, 1);
                header('Location: ?app=User/Home');
                return true;
            } else { // $db_password != $password; // invalid password
                $this->uModel->loginAttempt($userId, $now, 0);
                header('Location: ?app=User/Login&e=incorrect%20password');
            }
        } else {
            header('Location: ?app=User/Login&e=invalid%20login%20request');
        }
    }

    public function check() {
        $this->uModel = $this->model('UserModel');
        if (isset($_SESSION['user_id'], 
                    $_SESSION['username'], 
                    $_SESSION['login_string'])) {

            $userId = $_SESSION['user_id'];
            $loginString = $_SESSION['login_string'];
            $username = $_SESSION['username'];

            $userBrowser = $_SERVER['HTTP_USER_AGENT'];

            $row = $this->uModel->checkLogin($userId);
            if(count($row) != 0) {
                $password = $row['password'];
                $username = $row['username'];
                $loginCheck = hash('sha512', $password . $userBrowser);

                if ($loginCheck == $loginString) {
                    return true;
                }
            }
        }
        header('Location: ?app=User/Login');
        return false;
    }

    public function logout() {
        $_SESSION = array();

        $params = session_get_cookie_params();

        setcookie(session_name(),
                '', time() - 42000, 
                $params["path"], 
                $params["domain"], 
                $params["secure"], 
                $params["httponly"]);

        session_destroy();
        header('Location: ?app=User/home&e=Logged%20Out');
    }

    public function introEmail($to, $uniqueCode, 
             $urla = '?app=user/verify&act=', 
             $urlr = '?app=user/remove&act=')
    {
        $subject = 'Thank You For Registering';
        $message = 'To complete your registration,
        please verify your email using the following link:
        ' . DOMAIN . $urla . $uniqueCode . ' .';
        $message .= ' If you did not register using this email,
        you can remove your email using the following link:
        ' . DOMAIN . $urlr . $uniqueCode . ' .';

        $headers = 'From: registration@' . DOMAIN;
        $message = wordwrap($message, 70, "\r\n");
        if(DEBUG == 'OFF') {
            mail($to, $subject, $message, $headers);
            header('Location: ?app=User/Register&e=Success%20please%20check%20your%20email');
        } else {
            print_r($message);
        }
    }

}
?>