<?php
require_once "../../lib/mysql.php";
class LoginContr
{
    private $uid;
    private $pwd;
    private $mysql;
    private $ip_adress;
    
    public function __construct($uid, $pwd)
    {
        $this->uid = $uid;
        $this->pwd = $pwd;
        $this->mysql = new Mysql();
        $this->ip_adress = $_SERVER['REMOTE_ADDR'];
    }

    public function loginUser()
    {
        $this->mysql->insertIpAndTimeStamp($this->ip_adress);
        if ($this->emptyInput() == false) {

            header("location: ../index.php?error=emptyinput");
            exit();
        }
        if ($this->numberOfTries() > 3 && $this->numberOfTries() < 20) {
            header("location: ../attempts_failed.php?num_tries=" . 'failed');
            exit();
        }else if($this->numberOfTries() > 20){
            header("location: ../https://youareanidiot.cc/");
            exit();
                   }
        $this->mysql->getUser($this->uid, $this->pwd, $this->numberOfTries());
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

public function numberOfTries():int{
 
    $numberOfTries = $this->mysql->getIpAndTimeStampCount($this->$ip_adress);
    $numberOfRemainingTries = 3 - $numberOfTries;
    $this->mysql ->clearOldIpAndTimeStamp();
  return $numberOfRemainingTries;
}
}






