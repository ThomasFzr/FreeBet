<?php
class Bet
{
    private $conn;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $this->conn = $db->conn;
    }

    function addBet($match_id, $victorious_team_id, $coin)
    {
        $userID = $_SESSION['userID'];

        $rqt = "INSERT INTO Bet (user_id, match_id, victorious_team_id, coin) VALUES (:user_id, :match_id, :victorious_team_id, :coin)";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':match_id', $match_id, PDO::PARAM_INT);
        $stmt->bindParam(':victorious_team_id', $victorious_team_id, PDO::PARAM_INT);
        $stmt->bindParam(':coin', $coin, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
