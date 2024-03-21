<?php
class BetsFromUserController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function getBets()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Bet.php';
            $bet = new Bet();
            $bets = $bet->getBetsFromUser($_SESSION['userID']);
        } else {
            $bets = null;
        }
        echo $this->twig->render('bet/betView.twig', [
            'bets' => $bets
        ]);
    }
}
