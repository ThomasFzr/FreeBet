<?php
class GetFootballMatchController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function GetFootballMatch()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Bet.php';
            $userID = $_SESSION['userID'];
            $player = $player->getClassement($userID);
        }

        echo $this->twig->render(
            'classement/classement.twig',
            [
                'player' => $player
            ]
        );
    }
}
