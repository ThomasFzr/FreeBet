<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class FootballData
{
    private $conn;
    private $apiUrl;
    private $apiKey;
    function __construct()
    {
        require_once __DIR__ . '/../models/Database.php';
        $db = new Database();
        $this->conn = $db->conn;

        $apiUrl = 'http://api.football-data.org/v4/';
        $apiKey = '4ad5e5bf946f40b688c9aaea3402e519';
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    private function makeRequest($url)
    {
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: ' . $this->apiKey;
        $streamContext = stream_context_create($reqPrefs);
        sleep(1);
        return file_get_contents($url, false, $streamContext);
    }

    public function getTeams($teamNbr)
    {
        $url = $this->apiUrl . "teams/$teamNbr/matches";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    public function fillDatabaseFromApi()
    {
        $teamsData = $this->getTeams(523);
        $this->insertData('football_match', $teamsData);
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

    public function GetAllFootballMatches()
    {
        $rqt = "SELECT * FROM football_match";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
