<?php
require_once "../../lib/mysql.php";
class LoginContr
{
    private $uid;
    private $pwd;
    private $mysql;
    public function __construct($uid, $pwd)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->mysql = new Mysql();
    }

    public function loginUser()
    {
        if ($this->emptyInput() == false) {

            header("location: ../index.php?error=emptyinput");
            exit();
        }

        $this->mysql->getUser($this->uid, $this->pwd);
    }

    private function emptyInput()
    {
        $result = null;
        if (empty($this->uid) || empty($this->pwd)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}
