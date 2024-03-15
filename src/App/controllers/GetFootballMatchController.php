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
        $finishedFootballMatches = $football->GetFinishedFootballMatches();
        $notFinishedfootballMatches = $football->GetNotFinishedFootballMatches();

        echo $this->twig->render('football/footballView.twig', [
            'finishedFootballMatches' => $finishedFootballMatches,
            'notFinishedfootballMatches' => $notFinishedfootballMatches
        ]);
    }
}
