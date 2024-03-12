<?php
class TestController
{
    public function Test()
    {
        require_once __DIR__ . '/../models/Bet.php';
        $bet = new Bet();
        $bet->UpdateCoinFromBet($_SESSION["userID"]);
    }
}
