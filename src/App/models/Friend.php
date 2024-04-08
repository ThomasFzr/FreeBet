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

    public function getFriendsOfUser()
    {
        $user_id = $_SESSION['userID'];
        $rqt = "SELECT * FROM User 
            WHERE user_id IN (SELECT friend_id_2 from Friend where friend_id_1 = :user_id )
            OR user_id IN (SELECT friend_id_1 from Friend where friend_id_2 = :user_id );";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
