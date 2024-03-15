<?php
class AddBetController
{
    private $twig;
    private $betModel;

    public function __construct($twig)
    {
        $this->twig = $twig;
        require_once __DIR__ . '/../models/Bet.php';
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $conn = $db->conn;
        $this->betModel = new Bet($conn);
    }

    public function addBet()
    {
        // Mettre à jour les pièces de l'utilisateur en fonction des paris effectués
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['userID'])) {
            // Vérifier s'il y a des paris à ajouter
            
            if (isset($_POST['match_id'])) {
                try {
                    foreach ($_POST['match_id'] as $index => $matchId) {
                        $result = $_POST['result'][$index];
                        $amount = $_POST['amount'][$index];
                        // Ajouter le pari
                        $this->betModel->addBet($matchId, $result, $amount);
                    }
                    // Mettre à jour les pièces de l'utilisateur en fonction des paris effectués
                    $this->betModel->updateCoinFromBet($_SESSION['userID'], $_POST['totalBetAmount']);
                    $_SESSION['successMsg'] = "Paris ajouté(s).";
                } catch (Exception $e) {
                    $_SESSION['errorMsg'] = "Une erreur s'est produite lors de l'ajout des paris.";
                }
            } else {
                $_SESSION['errorMsg'] = "Aucun paris à ajouter.";
            }
            // Rediriger l'utilisateur vers la page de football après l'ajout des paris
            header('Location: /football');
        } else {
            // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
            header('Location: /connection');
        }
    }
}
