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
            echo "Connection failed: " . $e->getMessage();
        }
    }
    public function getData()
    {

        $query = 'SELECT * FROM Favourites';
        $stmt = $this->pdo->query($query);
        $gotData = $stmt->fetchAll();
        return $gotData;
    }
    public function insertData($id, $tag)
    {
        $query = 'INSERT INTO Favourites (id, tag) VALUES (:id,:tag) ON DUPLICATE KEY UPDATE id=:id, tag=:tag';
        $stmt = $this->pdo->prepare($query);

        return $stmt->execute(
            [
                ':id' => $id,
                ':tag' => $tag,
            ]
        );
    }

}
