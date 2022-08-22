<?php
/**
 * [Description mysql]
 */

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();
class mysql
{

    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;
    private $pdo;

    public function __construct()
    {
        $this->servername = getenv('DB_SERVERNAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->dbname = getenv('DB_NAME');
        $this->charset = getenv('DB_CHARSET');

        try {
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            throw new Exception("Database connection failed\n");
        }
    }
    public function getFavorites()
    {

        $query = 'SELECT * FROM Favourites';
        $stmt = $this->pdo->query($query);
        $gotData = $stmt->fetchAll();

        return $gotData;
    }

    public function insertFavorites($id, $tag, $users_id)
    {
        if ($users_id === null) {
            $users_id = 0;
        }
        $query = 'INSERT IGNORE INTO Favourites (id, tag, users_id) VALUES (:id,:tag, :users_id) ON DUPLICATE KEY UPDATE id=:id, tag=:tag, users_id=:users_id';
        $stmt = $this->pdo->prepare($query);

        $stmt->execute(
            [
                ':id' => $id,
                ':tag' => $tag,
                ':users_id' => $users_id,
            ]
        );
    }
    public function removeFavorites($tag)
    {
        $query = 'DELETE FROM Favourites WHERE tag = :tag';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(
            [
                ':tag' => $tag,
            ]
        );
    }

    //------------------------//
    //------------------------//
    public function setUser($uid, $pwd, $email)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (users_uid, users_pwd, users_email) VALUES (?, ?, ?);');

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        if (!$stmt->execute(array($uid, $hashedPwd, $email))) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }

        $stmt = null;
    }

    public function checkUser($uid, $email)
    {
        $stmt = $this->pdo->prepare('SELECT users_uid FROM users WHERE users_uid = ? OR users_email = ?;');

        if (!$stmt->execute(array($uid, $email))) {
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
    //------------------------//
    public function getUser($uid, $pwd)
    {
        $stmt = $this->pdo->prepare('SELECT users_pwd FROM users WHERE users_uid = ? OR users_email = ?;');

        if (!$stmt->execute([$uid, $pwd])) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
        if ($stmt->rowCount() == 0) {
            header("location: ../index.php?error=nouser");
        }

        $pwdHashed = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $checkPwd = password_verify($pwd, $pwdHashed[0]['users_pwd']);

        if ($checkPwd == false) {
            header("location: ../index.php?error=wrongpwd");
        } elseif ($checkPwd == true) {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE users_uid = ? OR users_email = ? AND USERS_PWD = ?;');

            if (!$stmt->execute([$uid, $uid, $pwd])) {
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
            $_SESSION['userid'] = $user[0]['users_id'];
            $_SESSION['useruid'] = $user[0]['users_uid'];

        }
        $stmt = null;
    }

    //------------------------//
    //------------------------//
    public function getUserFavourites($user_id)
    {
        $stmt = $this->pdo->prepare('SELECT tag FROM Favourites WHERE users_id = ?;');

        if (!$stmt->execute([$user_id])) {
            $stmt = null;
            header("location: ../index.php?error=stmtfailed");
            exit();
        }
        if ($stmt->rowCount() == 0) {
            header("location: ../no_favourites.php");
        }
        $userFavourites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($userFavourites as $favourite) {
            $favourites[] = $favourite['tag'];
        }

        return $favourites;
    }
}
