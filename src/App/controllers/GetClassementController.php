<?php

class GetClassementController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function GetClassement()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Bet.php';
            $bet = new Bet();
            $userID = $_SESSION['userID'];
            $players = $bet->getClassement($userID);
            echo $this->twig->render(
                'classement/classement.twig',
                [
                    'players' => $players
                ]
            );
        } else {
            header('Location: /connection');
        }
    }
}
