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
        // var_dump($teamsData);
        $this->insertOrUpdateData('Football_match', $teamsData);
    }

    public function insertOrUpdateData($tableName, $matchesInfo)
    {
        foreach ($matchesInfo['matches'] as $matchInfo) {
            // Extracting relevant information for each match
            $utcDate = new DateTime($matchInfo['utcDate']);
            $utcDateFormatted = $utcDate->format('d/m/Y');
            $status = $matchInfo['status'];
            $homeTeamId = $matchInfo['homeTeam']['id'];
            $homeTeamName = $matchInfo['homeTeam']['name'];
            $awayTeamId = $matchInfo['awayTeam']['id'];
            $awayTeamName = $matchInfo['awayTeam']['name'];
            if (isset($matchInfo['score']['fullTime']['home']) && isset($matchInfo['score']['fullTime']['away'])) {
                $homeTeamScore = $matchInfo['score']['fullTime']['home'];
                $awayTeamScore = $matchInfo['score']['fullTime']['away'];
            } else {
                $homeTeamScore = null;
                $awayTeamScore = null;
            }
            if ($homeTeamId == 523) {
                $opponent_team_id = $awayTeamId;
                $opponent_team_name = $awayTeamName;
                $OL_score = $homeTeamScore;
                $opponent_score = $awayTeamScore;
            } else {
                $opponent_team_id = $homeTeamId;
                $opponent_team_name = $homeTeamName;
                $OL_score = $awayTeamScore;
                $opponent_score = $homeTeamScore;
            }

            // Check if the record already exists based on the date
            $existingRecordQuery = "SELECT * FROM $tableName WHERE `date` = :utcDate";
            $existingRecordStmt = $this->conn->prepare($existingRecordQuery);
            $existingRecordStmt->bindParam(':utcDate', $utcDateFormatted);
            $existingRecordStmt->execute();

            if ($existingRecordStmt->rowCount() > 0) {
                // If record exists, update the values
                $updateQuery = "UPDATE $tableName SET 
                                `status` = :status,
                                `opponent_team_id` = :opponent_team_id,
                                `opponent_team_name` = :opponent_team_name,
                                `OL_score` = :OL_score,
                                `opponent_score` = :opponent_score
                                WHERE `date` = :utcDate";

                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':utcDate', $utcDateFormatted);  // Add this line
            } else {
                // If record does not exist, insert a new record
                $updateQuery = "INSERT INTO $tableName 
                                (`date`, `status`, `opponent_team_id`, `opponent_team_name`,`OL_score`, `opponent_score`)
                                VALUES
                                (:utcDate, :status, :opponent_team_id, :opponent_team_name,:OL_score, :opponent_score)";

                $updateStmt = $this->conn->prepare($updateQuery);
            }

            // Bind parameters for update/insert
            $updateStmt->bindParam(':utcDate', $utcDateFormatted);  // Add this line
            $updateStmt->bindParam(':status', $status);
            $updateStmt->bindParam(':opponent_team_id', $opponent_team_id);
            $updateStmt->bindParam(':opponent_team_name', $opponent_team_name);
            $updateStmt->bindParam(':OL_score', $OL_score);
            $updateStmt->bindParam(':opponent_score', $opponent_score);

            // Execute the update/insert query
            $updateStmt->execute();
        }
    }

    public function GetAllFootballMatches()
    {
        $rqt = "SELECT * FROM Football_match";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetFinishedFootballMatches()
    {
        $rqt = "SELECT * FROM Football_match
                WHERE status = 'FINISHED'
                ORDER BY match_id desc";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetNotFinishedFootballMatches()
    {
        $rqt = "SELECT * FROM Football_match
                WHERE status != 'FINISHED'";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetStatus($match_id)
    {
        $rqt = "SELECT status
            FROM Football_match
            WHERE match_id = $match_id";
        $stmt = $this->conn->prepare($rqt);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
