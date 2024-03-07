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
        require_once __DIR__ . '/../models/FootballData.php';
        $football = new FootballData();
        $footballMatches = $football->GetAllFootballMatches();

        echo $this->twig->render('footballView.twig', [
            'footballMatches' => $footballMatches,
        ]);
    }
}
