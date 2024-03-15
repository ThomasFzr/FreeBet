<?php

class Bet
{
    private $conn;

    function __construct($conn)
    {
        $this->conn = $conn;
    }

    function addBet($match_id, $victorious_team_id, $coin)
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['userID'])) {
            return false; // Retourner false si l'utilisateur n'est pas connecté
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
            return true; // Retourner true si l'insertion est réussie
        } else {
            return false; // Retourner false en cas d'échec de l'insertion
        }
    }

    public function updateCoinFromBet($user_id, $total_bet_amount)
    {
        // Sélectionner les paris de l'utilisateur
        $stmt = $this->conn->prepare("SELECT coin FROM Bet WHERE user_id = :user_id AND updated = 0");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $userBets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculer le montant total des paris de l'utilisateur
        $total_bet_amount = 0;
        foreach ($userBets as $userBet) {
            $total_bet_amount += $userBet['coin'];
        }

        // Mettre à jour le solde de pièces de l'utilisateur
        $stmtUpdateUser = $this->conn->prepare("UPDATE User SET coin = coin - :total_bet_amount WHERE user_id = :user_id");
        $stmtUpdateUser->bindParam(':total_bet_amount', $total_bet_amount, PDO::PARAM_INT);
        $stmtUpdateUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUpdateUser->execute();

        // Mettre à jour le statut des paris
        $stmtUpdateBet = $this->conn->prepare("UPDATE Bet SET updated = 1 WHERE user_id = :user_id AND updated = 0");
        $stmtUpdateBet->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmtUpdateBet->execute();
    }
}

// Récupérer l'ID de l'utilisateur connecté et le montant total du pari depuis les données postées
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$totalBetAmount = isset($_POST['totalBetAmount']) ? $_POST['totalBetAmount'] : null;

// Instancier la classe Bet avec la connexion à la base de données
require_once __DIR__ . '/../models/Database.php';
$db = new Database();
$conn = $db->conn;
$bet = new Bet($conn);

// Appeler la méthode pour mettre à jour le solde des pièces de l'utilisateur
if ($userID !== null && $totalBetAmount !== null) {
    $bet->updateCoinFromBet($userID, $totalBetAmount);
}
