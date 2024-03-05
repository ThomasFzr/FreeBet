<?php

class FootballController
{
    public function getFootballMatches()
    {
        require_once __DIR__ . '/../models/FootballData.php';
        $apiKey = '4ad5e5bf946f40b688c9aaea3402e519';
        $model = new FootballData();

        $apiUrl = 'http://api.football-data.org/v4/';
        $model->fillDatabaseFromApi($apiUrl, $apiKey);  
    }
}
?>