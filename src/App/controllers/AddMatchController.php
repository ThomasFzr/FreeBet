<?php
class AddMatchController
{
    public function AddMatch()
    {
        if (isset($_SESSION['isAdmin'])) {
            require_once __DIR__ . '/../models/FootballData.php';
            $footballData = new FootballData();

            $selectedValue = $_POST['selectedTeam'];
            list($opponent_team_id, $opponent_team_name) = explode('|', $selectedValue);

            $matchesInfo = [
                'matches' => [
                    [
                        'utcDate' => $_POST['date'],
                        'status' => $_POST['status'],
                        'homeTeam' => ['id' => 523, 'shortName' => "OL"],
                        'awayTeam' => ['id' => $opponent_team_id, 'shortName' => $opponent_team_name],
                        'OL_score'
                    ],
                ]
            ];
            $footballData->insertOrUpdateData("Football_match", $matchesInfo);
            header('Location: /admin/football');
            $_SESSION['successMsg'] = "Match ajouté avec succès.";
        } else {
            header('Location: /connection');
        }
    }
}
