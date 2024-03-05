<?php
class User
{

    private $conn;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $this->conn = $db->conn;
    }


    //Récupération données user
    public function getUserInfo($email)
    {
        $rqt = "SELECT * FROM User WHERE mail = :email";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //===== ADMIN =====

    function getAllUser()
    {
        $rqt = "SELECT * FROM User";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function addUser($mail, $pwd, $isAdmin, $name, $surname, $phoneNbr)
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
            $sql = "INSERT INTO User (mail, pwd, isAdmin, name, surname, phoneNbr) VALUES (:mail,:pwd,:isAdmin,:name,:surname,:phoneNbr)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':pwd', $hash, PDO::PARAM_STR);
            $stmt->bindParam(':isAdmin', $isAdmin, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':phoneNbr', $phoneNbr, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $_SESSION['successMsg'] = "Utilisateur créé!";
                return true;
            } else {
                $_SESSION['errorMsg'] = "Utilsateur non créé.";
                return false;
            }
        }
    }

    function deleteUser($userID)
    {
        $rqt = "DELETE FROM Favorite
                  WHERE userID = :userID;
                  DELETE FROM Reservation
                  WHERE userID = :userID;
                  DELETE FROM Review
                  WHERE userID = :userID;
                  DELETE FROM User
                  WHERE userID = :userID;";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $_SESSION['successMsg'] = "Utilisateur supprimé!";
            return true;
        } else {
            $_SESSION['errorMsg'] = "Utilisateur non supprimé.";
            return false;
        }
    }

    function modifyUser($column, $value, $userID)
    {
        $rqt = "UPDATE User SET $column = :value WHERE userID=:userID";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam('value', $value, PDO::PARAM_STR);
        $stmt->bindParam('userID', $userID, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
