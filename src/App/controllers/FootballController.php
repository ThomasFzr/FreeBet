<?php
class FootballController
{
    public function getFootballMatches()
    {
        require_once __DIR__ . '/../models/FootballData.php';
        $model = new FootballData();
        $model->fillDatabaseFromApi();
    }
}
