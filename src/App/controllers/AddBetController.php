<?php
class AddBetController
{
    public function AddBet()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Bet.php';
            $bet = new Bet();

            if (isset($_POST['match_id'])) {
                foreach ($_POST['match_id'] as $index => $matchId) {
                    $result = $_POST['result'][$index];
                    $amount = $_POST['amount'][$index];

                    $bet->addBet($matchId, $result, $amount);


                    $_SESSION['successMsg'] = "Paris ajouté(s).";
                }
            } else {
                $_SESSION['errorMsg'] = "Aucun paris.";
            }
            header('Location: /football');
        } else {
            header('Location: /connection');
        }
    }
}
