<?php
class Connection
{

    private $conn;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $this->conn = $db->conn;
    }

    //Login and get user info
    public function authenticateUser($mail, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM User WHERE mail = :mail");
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row)) {
            if (password_verify($password, $row['pwd'])) {
                $_SESSION['isConnected'] = true;
                $_SESSION['mail'] = $mail;
                $_SESSION['userID'] = $row['user_id'];
                $_SESSION['surname'] = $row['surname'];
                $_SESSION['isAdmin'] = $row['isAdmin'];
                $_SESSION['coin'] = $row['coin'];

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
