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
        $this->updateCoinFromBet($_SESSION['userID'], $coin);
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['userID'])) {
            return false; // Retourner false si l'utilisateur n'est pas connecté
        }

        // Vérifier si le montant du pari est valide
        if ($coin <= 0) {
            return false; // Retourner false si le montant du pari est invalide
        }

        // Récupérer l'ID de l'utilisateur connecté
        $userID = $_SESSION['userID'];

        // Préparer la requête d'insertion du pari
        $rqt = "INSERT INTO Bet (user_id, match_id, victorious_team_id, coin) VALUES (:user_id, :match_id, :victorious_team_id, :coin)";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindParam(':match_id', $match_id, PDO::PARAM_INT);
        $stmt->bindParam(':victorious_team_id', $victorious_team_id, PDO::PARAM_INT);
        $stmt->bindParam(':coin', $coin, PDO::PARAM_INT);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Effectuer une requête pour récupérer le solde de l'utilisateur à partir de la base de données
            $stmt = $this->conn->prepare("SELECT coin FROM User WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $coin_user = $stmt->fetchColumn();

            // Stocker le solde de l'utilisateur dans la session utilisateur
            $_SESSION['coin_user'] = $coin_user;
            return true; // Retourner true si l'insertion est réussie
        } else {
            return false; // Retourner false en cas d'échec de l'insertion
        }
    }

    public function updateCoinFromBet($user_id, $total_bet_amount)
    {
        // Mettre à jour le solde de pièces de l'utilisateur
        $stmtUpdateUser = $this->conn->prepare("UPDATE User SET coin = coin - :total_bet_amount WHERE user_id = :user_id");
        $stmtUpdateUser->bindParam(':total_bet_amount', $total_bet_amount, PDO::PARAM_INT);
        $stmtUpdateUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUpdateUser->execute();
    }

    public function addCoinFromBet($user_id)
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
                    $_SESSION['coin_user'] = $coin;
                    $stmtUpdateUser = $this->conn->prepare("UPDATE User SET `coin` = :money WHERE user_id = :user_id");
                    $stmtUpdateUser->bindParam(':money', $updatedCoin, PDO::PARAM_INT);
                    $stmtUpdateUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmtUpdateUser->execute();
                }
                $stmtUpdateBet = $this->conn->prepare("UPDATE Bet SET `updated` = 1 WHERE bet_id = :bet_id");
                $stmtUpdateBet->bindParam(':bet_id', $userBet['bet_id'], PDO::PARAM_INT);
                $stmtUpdateBet->execute();
                header('Location: /');
            }
        }
    }

    public function GetClassement($userID)
    {
        // Sélectionner les amis de l'utilisateur avec l'ID donné
        $sql = "SELECT sum(b.coin) as Somme, u.*
        FROM Bet b
        JOIN User u ON u.user_id = b.user_id
        WHERE u.user_id IN (
            SELECT user_id 
            FROM User 
            WHERE user_id IN (SELECT friend_id_2 FROM Friend WHERE friend_id_1 = :user_id)
               OR user_id IN (SELECT friend_id_1 FROM Friend WHERE friend_id_2 = :user_id)
          UNION
            SELECT :user_id
        )
        GROUP BY b.user_id, u.user_id
        ORDER BY sum(b.coin) DESC;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBetsFromUser($user_id)
    {
        $rqt = "SELECT b.*, f.*, b.victorious_team_id AS victorious_team_id_bet
        FROM Bet b
        JOIN Football_match f ON f.match_id = b.match_id
        WHERE user_id = :user_id
        ORDER BY bet_id desc";
        $stmt = $this->conn->prepare($rqt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
