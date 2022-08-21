<?php
require_once "../../lib/mysql.php";
class SignupContr
{
    private $mysql;
    private $uid;
    private $pwd;
    private $pwdRepeat;
    private $email;

    public function __construct($uid, $pwd, $pwdRepeat, $email)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->email = $email;

        $this->mysql = new Mysql(
            $this->servername = getenv('DB_SERVERNAME'),
            $this->username = getenv('DB_USERNAME'),
            $this->password = getenv('DB_PASSWORD'),
            $this->dbname = getenv('DB_NAME_USERS'),
            $this->charset = getenv('DB_CHARSET')
        );
    }

    public function signupUser()
    {
        if ($this->emptyInput() == false) {
            // echo "Empty input!";
            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if ($this->invalidUid() == false) {
            // echo "Invalid username!";
            header("location: ../index.php?error=username");
            exit();
        }
        if ($this->invalidEmail() == false) {
            // echo "Invalid email!";
            header("location: ../index.php?error=email");
            exit();
        }
        if ($this->pwdMatch() == false) {
            // echo "Passwords don't match!";
            header("location: ../index.php?error=passwordmatch");
            exit();
        }
        if ($this->uidTakenCheck() == false) {
            // echo "Username or email taken!";
            header("location: ../index.php?error=useroremailtaken");
            exit();
        }

        $this->mysql->setUser($this->uid, $this->pwd, $this->email);
    }

    private function emptyInput()
    {
        $result = null;
        if (empty($this->uid) || empty($this->pwd) || empty($this->pwdRepeat) || empty($this->email)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidUid()
    {
        $result = null;
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function invalidEmail()
    {
        $result = null;
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function pwdMatch()
    {
        $result = "";
        if ($this->pwd !== $this->pwdRepeat) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }

    private function uidTakenCheck()
    {
        $result = "";
        if (!$this->mysql->checkUser($this->uid, $this->email)) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }
}
