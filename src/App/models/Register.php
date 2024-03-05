<?php
class Register
{

    private $conn;
    private $user;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';
        $db = new Database();
        $this->conn = $db->conn;
        $this->user = new User();
    }

    //Insert inscription and get user info
    function insertIntoTableRegister($mail, $pwd)
    {
        $rqt = "SELECT * from User where mail = :mail";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(":mail", $mail, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['errorMsg'] = "Un compte existe déjà avec cette adresse mail.";
            return false;
        } else {
            $hash = password_hash($pwd, PASSWORD_DEFAULT);
            $sql = "INSERT INTO User (mail, pwd) VALUES ('$mail', '$hash')";
            $this->conn->exec($sql);
            $_SESSION['isConnected'] = true;
            $_SESSION['mail'] = $mail;
            $_SESSION['userID'] = $this->user->getUserInfo($mail)['userID'];
            $_SESSION['successMsg'] = "Bienvenue!";
            return true;
        }
    }
}