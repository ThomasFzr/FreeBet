<?php
class AdminFootballController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function AdminFootball()
    {
        if (isset($_SESSION['isAdmin'])) {
            require_once __DIR__ . '/../models/FootballData.php';
            $football = new FootballData();
            $finishedFootballMatches = $football->GetFinishedFootballMatches();
            $notFinishedfootballMatches = $football->GetNotFinishedFootballMatches();
            $allTeams = $football->GetAllTeams();

            echo $this->twig->render('adminFootball/adminFootballView.twig', [
                'finishedFootballMatches' => $finishedFootballMatches,
                'notFinishedfootballMatches' => $notFinishedfootballMatches,
                'allTeams' => $allTeams
            ]);
        }
    }
}
