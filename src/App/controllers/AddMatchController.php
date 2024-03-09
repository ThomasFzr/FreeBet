<?php
class AddMatchController
{
    public function AddMatch()
    {
        if (isset($_SESSION['isAdmin'])) {
            require_once __DIR__ . '/../models/FootballData.php';
            $footballData = new FootballData();


            $matchesInfo = [
                'matches' => [
                    [
                        'utcDate' => $_POST['date'],
                        'status' => $_POST['status'],
                        'homeTeam' => ['id' => 523],
                        'awayTeam' => ['id' => $_POST['opponent-id']],
                        'OL_score'
                    ],
                ]
            ];
            $footballData->insertOrUpdateData("Football_match", $matchesInfo);
            header('Location: /addMatch');
            $_SESSION['successMsg'] = "Match ajouté avec succès.";
        } else {
            header('Location: /connection');
        }
    }
}
