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

            echo $this->twig->render('adminFootballView.twig', [
                'finishedFootballMatches' => $finishedFootballMatches,
                'notFinishedfootballMatches' => $notFinishedfootballMatches
            ]);
        }
    }
}
