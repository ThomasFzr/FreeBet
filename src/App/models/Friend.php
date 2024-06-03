<?php

class Friend
{
    private $conn;

    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $this->conn = $db->conn;
    }

    // public function getFriendsOfUser()
    // {
    //     $user_id = $_SESSION['userID'];
    //     $rqt = "SELECT * FROM User 
    //         WHERE user_id IN (SELECT friend_id_2 from Friend where friend_id_1 = :user_id )
    //         OR user_id IN (SELECT friend_id_1 from Friend where friend_id_2 = :user_id );";
    //     $stmt = $this->conn->prepare($rqt);
    //     $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);   
    // }
    public function getFriendsOfUser($userID)
    {
        // Sélectionner les amis de l'utilisateur avec l'ID donné
        $sql = "SELECT * FROM User 
                WHERE user_id IN (
                    SELECT friend_id_2 FROM Friend WHERE friend_id_1 = :user_id
                    UNION
                    SELECT friend_id_1 FROM Friend WHERE friend_id_2 = :user_id
                )";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function addFriendByEmail($friendEmail)
    {
        // Vérifier si l'utilisateur avec cette adresse e-mail existe
        $rqt = "SELECT * FROM User WHERE mail = :mail";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(":mail", $friendEmail, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Récupérer l'ID de l'ami associé à l'adresse e-mail spécifiée
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $friendID = $result['user_id'];
            $userid = $_SESSION['userID'];
            // Insérer l'ami dans la table Friend
            $sql = "INSERT INTO Friend (friend_id_1, friend_id_2) VALUES (:user_id, :friend_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':friend_id', $friendID, PDO::PARAM_INT);
            $stmt->execute();

            return true; // Succès de l'ajout de l'ami
        } else {
            return false; // Aucun utilisateur trouvé avec cette adresse e-mail
        }
    }
}
