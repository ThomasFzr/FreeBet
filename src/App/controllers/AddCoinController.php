<?php
class AddCoinController
{
    public function AddCoin()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Bet.php';
            $bet = new Bet();
            $bet->addCoinFromBet($_SESSION["userID"]);
        }
    }
}
