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

<<<<<<< HEAD
    public function UpdateCoinFromBet($user_id)
    {
        $rqt = "SELECT b.*, f.*, b.victorious_team_id AS victorious_team_id_bet
        FROM Bet b
        JOIN Football_match f ON f.match_id = b.match_id
        WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();
        $userBets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rqt2 = "SELECT coin
                FROM User
                WHERE user_id = $user_id";
        $stmt2 = $this->conn->prepare($rqt2);
        $stmt2->execute();
        $coin = $stmt2->fetchColumn();


        foreach ($userBets as $userBet) {
            if ($userBet['status'] == "FINISHED" && !$userBet['updated']) {
                $betAmount = $userBet['coin'];
                if ($userBet["victorious_team_id_bet"] == $userBet["victorious_team_id"]) {
                    $updatedCoin = $coin + ($betAmount * 2);
                    $coin += $betAmount * 2;
                    $stmtUpdateUser = $this->conn->prepare("UPDATE User SET `coin` = :money WHERE user_id = :user_id");
                    $stmtUpdateUser->bindParam(':money', $updatedCoin, PDO::PARAM_INT);
                    $stmtUpdateUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmtUpdateUser->execute();
                }
                $stmtUpdateBet = $this->conn->prepare("UPDATE Bet SET `updated` = 1 WHERE bet_id = :bet_id");
                $stmtUpdateBet->bindParam(':bet_id', $userBet['bet_id'], PDO::PARAM_INT);
                $stmtUpdateBet->execute();
            }
        }
=======
    public function GetBetOfUser($user_id)
    {
        $rqt = "SELECT * FROM Bet b
                JOIN Football_match f
                ON f.match_id = b.match_id
                WHERE user_id =  $user_id";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> 5c091c0 (add name of opponent team in bdd)
    }
}
