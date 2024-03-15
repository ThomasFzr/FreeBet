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
