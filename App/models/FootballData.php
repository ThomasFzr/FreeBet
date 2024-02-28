<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class FootballData
{
    private $conn;
    function __construct()
    {
        echo "FootballData __construct method is called.\n";
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
        $this->insertData('football_matches', $teamsData);
        // $this->insertData('persons', $personsData);
        // $this->insertData('matches', $matchesData);
    }



    private function insertData($tableName, $matchesInfo)
    {
        foreach ($matchesInfo['matches'] as $matchInfo) {
            // Extracting relevant information for each match
            $matchId = $matchInfo['id'];
            $utcDate = new DateTime($matchInfo['utcDate']);
            $utcDateFormatted = $utcDate->format('Y-m-d H:i:s');
            $status = $matchInfo['status'];
            $matchday = $matchInfo['matchday'];
            $stage = $matchInfo['stage'];
            $homeTeamId = $matchInfo['homeTeam']['id'];
            $awayTeamId = $matchInfo['awayTeam']['id'];
            $homeTeamScore = $matchInfo['score']['fullTime']['home'];
            $awayTeamScore = $matchInfo['score']['fullTime']['away'];
            $refereeId = isset($matchInfo['referees'][0]['id']) ? $matchInfo['referees'][0]['id'] : null;
    
            try {
                // Using PDO to prepare and execute the query
                $query = "INSERT INTO $tableName 
                          (`match_id`, `utc_date`, `status`, `matchday`, `stage`, `home_team_id`, `away_team_id`, `home_team_score`, `away_team_score`, `referee_id`)
                          VALUES
                          (:matchId, :utcDate, :status, :matchday, :stage, :homeTeamId, :awayTeamId, :homeTeamScore, :awayTeamScore, :refereeId)";
    
                $stmt = $this->conn->prepare($query);
    
                // Bind parameters
                $stmt->bindParam(':matchId', $matchId);
                $stmt->bindParam(':utcDate', $utcDateFormatted); // Use the formatted datetime string
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':matchday', $matchday);
                $stmt->bindParam(':stage', $stage);
                $stmt->bindParam(':homeTeamId', $homeTeamId);
                $stmt->bindParam(':awayTeamId', $awayTeamId);
                $stmt->bindParam(':homeTeamScore', $homeTeamScore);
                $stmt->bindParam(':awayTeamScore', $awayTeamScore);
                $stmt->bindParam(':refereeId', $refereeId);
    
                $stmt->execute();
    
                echo "Match info for match ID $matchId inserted successfully!<br>";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
    
}
