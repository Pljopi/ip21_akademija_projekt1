<?php
/**
 * [Description mysql]
 */

require_once __DIR__ . "/../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . "/../");
$dotenv->load();
class mysql
{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;
    private $pdo;
    private $ip_adress;

    public function __construct()
    {
        $this->servername = getenv("DB_SERVERNAME");
        $this->username = getenv("DB_USERNAME");
        $this->password = getenv("DB_PASSWORD");
        $this->dbname = getenv("DB_NAME");
        $this->charset = getenv("DB_CHARSET");
        $this->ip_adress = $_SERVER['REMOTE_ADDR'];
        try {
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed\n");
        }
    }

    //------------------------//
    //General use, console + web ++ kaj imam tukaj PDOException? ali header? za errorje?
    //------------------------//

    public function getFavorites()
    {
        $query = "SELECT tag FROM Favourites WHERE users_id = '0'";
        $stmt = $this->pdo->query($query);
        $gotData = $stmt->fetchAll();

        return $gotData;
    }




    public function insertFavorites($id, $tag, $users_id)
    {
        //users_id=0 means that the user is not logged in
        if (!is_numeric($users_id)) {
            $users_id = 0;
        }
        $query = "INSERT IGNORE INTO Favourites (id, tag, users_id) VALUES (:id,:tag, :users_id) ON DUPLICATE KEY UPDATE id=:id, tag=:tag, users_id=:users_id";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute(
            [
            ":id" => $id,
            ":tag" => $tag,
            ":users_id" => $users_id,
            ]
        );
    }
    public function removeFavorites($tag)
    {
        $query = "DELETE FROM Favourites WHERE tag = :tag";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
            ":tag" => $tag,
            ]
        );
    }

    //------------------------//
    //Signup
    //------------------------//
    public function setUser($uid, $pwd, $email)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (users_uid, users_pwd, users_email) VALUES (:users_uid, :users_pwd, :users_email);"
        );

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute(
            [
            ":users_uid" => $uid,
            ":users_pwd" => $hashedPwd,
            ":users_email" => $email,
            ]
        )
        ) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }

    public function checkUser($uid, $email)
    {
        $query =
        "SELECT users_uid FROM users WHERE users_uid = :users_uid OR users_email = :users_email;";
        $stmt = $this->pdo->prepare($query);

        if (!$stmt->execute(
            [
            ":users_uid" => $uid,
            ":users_email" => $email,
            ]
        )
        ) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $resultCheck = "";
        if ($stmt->rowCount() > 0) {
            $resultCheck = false;
        } else {
            $resultCheck = true;
        }

        return $resultCheck;
    }
    //------------------------//
    //Login
    //------------------------//
    public function getUser($uid, $pwd, $numberOfTries)
    {
        $query = "SELECT users_pwd FROM users WHERE users_uid = :users OR users_email = :users;";
        $stmt = $this->pdo->prepare($query);

        if (!$stmt->execute(
            [
            ":users" => $uid,
            ":users_pwd" => $pwd,
            ]
        )
        ) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
        if ($stmt->rowCount() == 0) {
            header("location: ../index.php?error=nouser");
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]["users_pwd"]);

        if ($checkPwd == false) {
            header("location: ../attempts_failed.php?num_tries=" . $numberOfTries);

        } elseif ($checkPwd == true) {
            $query ="SELECT * FROM users WHERE users_uid = :users_uid OR users_email = :users_email AND USERS_PWD = :users_pwd;";
            $stmt = $this->pdo->prepare($query);

            if (!$stmt->execute(
                [
                ":users_uid" => $uid,
                ":users_email" => $uid,
                ":users_pwd" => $pwd,
                ]
            )
            ) {
                $stmt = null;
                header("location: ../index.php?error=stmtfailed");
                exit();
            }

            if ($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: ../index.php?error=nouser");
            }
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            session_start();
            
            $_SESSION["userid"] = $user[0]["users_id"];
            $_SESSION["useruid"] = $user[0]["users_uid"];
            $this->clearAllIpAndTimeStamp($this->ip_adress);
            header("Location: ../Index.php?Welcome");
        }
        $stmt = null;
    }

    //------------------------//
    //User-specific interactions
    //------------------------//
    /**
     * @param  int $user_id
     * 'tag' is favourite currency TAG
     * @return ARRAY
     */
    public function getUserFavourites($user_id){
        $query="SELECT tag FROM Favourites WHERE users_id = :user_id;";
        $stmt = $this->pdo->prepare($query);

        if (!$stmt->execute(
            [
            ":user_id" => $user_id,
            ]
        )
        ) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed"); // te headerje naredi v twig template
            exit();
        }
        if ($stmt->rowCount() == 0) {
            header("location: ../no_favourites.php");
        }

        $getUserFavourites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($getUserFavourites as $favourite) {
            $userFavourites[] = $favourite["tag"];
        }

        return $userFavourites;
    }

    //------------------------//
    //IP-specific interactions
    //------------------------//

    public function insertIpAndTimeStamp($ip_adress){
        $time = date("Y-m-d H:i:s");
        $query=  "INSERT INTO IPadress (ip_adress, TIME) VALUES (:ip_adress, :TIME);";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
            ":ip_adress" => $ip_adress,
            ":TIME" => $time,
            ]
            
        );
    }
        public function getIpAndTimeStampCount($ip_adress){
        $query =   "SELECT TIME FROM IPadress WHERE ip_adress = :ip_adress AND TIME > DATE_SUB(NOW(), INTERVAL 1 hour) ORDER BY TIME DESC;";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute(
            [
            ":ip_adress" => $ip_adress,
            ]
        );
        $getIpAndTimeStampCount = $stmt->rowCount();
        
        return $getIpAndTimeStampCount;
       
    }

    //----------Clears IP And Time Stamp older than 1h--------------//
    public function clearOldIpAndTimeStamp(){
        $query =  "DELETE FROM IPadress WHERE TIME < DATE_SUB(NOW(), INTERVAL 1 hour);";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    }
//----------Clears all IP And Time Stamp associated with the user's IP on sucessfull log in. --------------//
    public function clearAllIpAndTimeStamp($ip_adress){
        $query =  "DELETE FROM IPadress WHERE :ip_adress = ip_adress;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
            ":ip_adress" => $this->ip_adress,
            ]
        );
    
    }
   

}
