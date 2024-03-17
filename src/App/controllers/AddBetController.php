<?php
class AddBetController
{
    private $bet;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Bet.php';
        $this->bet = new Bet();
    }

    public function addBet()
    {
        if (isset($_SESSION['userID'])) {

            if (isset($_POST['match_id'])) {
                try {
                    foreach ($_POST['match_id'] as $index => $matchId) {
                        $result = $_POST['result'][$index];
                        $amount = $_POST['amount'][$index];
                        // Ajouter le pari
                        $this->bet->addBet($matchId, $result, $amount);
                    }
                    $_SESSION['successMsg'] = "Paris ajouté(s).";
                } catch (Exception $e) {
                    $_SESSION['errorMsg'] = "Une erreur s'est produite lors de l'ajout des paris.";
                }
            } else {
                $_SESSION['errorMsg'] = "Aucun paris à ajouter.";
            }
            header('Location: /football');
        } else {
            header('Location: /connection');
        }
    }
}
