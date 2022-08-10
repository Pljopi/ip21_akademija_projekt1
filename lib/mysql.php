<?php
/**
 * [Description mysql]
 */
class mysql
{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;

    /**
     * @return [type]
     */
    public function connect()
    {
        $this->servername = "php_app_db:3306";
        $this->username = "root";
        $this->password = "root";
        $this->dbname = "crypto";
        $this->charset = "utf8mb4";
        try {
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=$this->charset";
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

}
