<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'vendor/autoload.php';

class Database
{
    private $host = 'mysql';
    private $dbname = 'my_database';
    private $username = 'my_user';
    private $password = 'my_password';
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Connection failed (func construct): " . $e->getMessage());
        }
    }

    public function createTables()
    {
        $sql = "SHOW TABLES LIKE 'User'";
        $result = $this->conn->query($sql);
        if ($result->rowCount() == 0) {
            $sql = "CREATE TABLE User (user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), surname VARCHAR(255), mail VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, phoneNbr VARCHAR(255), isAdmin BOOL NOT NULL DEFAULT false, UNIQUE(mail));
                    CREATE TABLE IF NOT EXISTS football_match (match_id INT NOT NULL PRIMARY KEY, `utc_date` DATETIME NOT NULL, status VARCHAR(50) NOT NULL, matchday INT NOT NULL, stage VARCHAR(50) NOT NULL, home_team_id INT NOT NULL, away_team_id INT NOT NULL, home_team_score INT, away_team_score INT, referee_id INT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
                    CREATE TABLE IF NOT EXISTS Bet (match_id INT NOT NULL, user_id INT NOT NULL, victorious_team_id INT NOT NULL, PRIMARY KEY (match_id, user_id), FOREIGN KEY (match_id) REFERENCES football_match(match_id), FOREIGN KEY (user_id) REFERENCES User(user_id));
                    ";
            $this->conn->exec($sql);
            return true;
        } else {
            return false;
        }
    }

    public function updateTable($table, $column, $value, $email)
    {
        $rqt = "UPDATE $table SET $column = :value WHERE mail = :email";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
