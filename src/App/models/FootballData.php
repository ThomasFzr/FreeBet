<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class FootballData
{
    private $conn;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/FootballDataService.php';
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function fillDatabaseFromApi($apiUrl, $apiKey)
    {
        $footballDataService = new FootballDataService($apiUrl, $apiKey);

        // Fetch data from the API
        // $areasData = $footballDataService->getAreas();
        // $competitionsData = $footballDataService->getCompetitions();
        $teamsData = $footballDataService->getTeams(523);
        // $personsData = $footballDataService->getPersons(12);
        // $matchesData = $footballDataService->getMatches();

        // Insert data into the database
        // $this->insertData('areas', $areasData);
        // $this->insertData('competitions', $competitionsData);
        $this->insertData('football_match', $teamsData);
        // $this->insertData('persons', $personsData);
        // $this->insertData('matches', $matchesData);
    }



    public function insertData($tableName, $matchesInfo)
    {
        foreach ($matchesInfo['matches'] as $matchInfo) {
            // Extracting relevant information for each match
            $utcDate = new DateTime($matchInfo['utcDate']);
            $utcDateFormatted = $utcDate->format('d/m/Y');
            $status = $matchInfo['status'];
            $homeTeamId = $matchInfo['homeTeam']['id'];
            $awayTeamId = $matchInfo['awayTeam']['id'];
            if (isset($matchInfo['score']['fullTime']['home']) && isset($matchInfo['score']['fullTime']['away'])) {
                $homeTeamScore = $matchInfo['score']['fullTime']['home'];
                $awayTeamScore = $matchInfo['score']['fullTime']['away'];
            } else {
                $homeTeamScore = null;
                $awayTeamScore = null;
            }
            if ($homeTeamId == 523) {
                $opponent_team_id = $awayTeamId;
                $OL_score = $homeTeamScore;
                $opponent_score = $awayTeamScore;
            } else {
                $opponent_team_id = $homeTeamId;
                $OL_score = $awayTeamScore;
                $opponent_score = $homeTeamScore;
            }

            // Using PDO to prepare and execute the query
            $query = "INSERT INTO $tableName 
                          (`date`, `status`, `opponent_team_id`, `OL_score`, `opponent_score`)
                          VALUES
                          (:utcDate, :status, :opponent_team_id, :OL_score, :opponent_score)";

            $stmt = $this->conn->prepare($query);

            // Bind parameters
            $stmt->bindParam(':utcDate', $utcDateFormatted); // Use the formatted datetime string
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':opponent_team_id', $opponent_team_id);
            $stmt->bindParam(':OL_score', $OL_score);
            $stmt->bindParam(':opponent_score', $opponent_score);

            $stmt->execute();
        }
    }
}
