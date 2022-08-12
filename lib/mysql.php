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

    public function __construct($servername, $username, $password, $dbname, $charset)
    {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->charset = $charset;

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
