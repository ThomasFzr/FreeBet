<?php

class FootballDataService {
    private $apiUrl;
    private $apiKey;

    public function __construct($apiUrl, $apiKey) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    public function getAreas() {
        echo "getAreas method is called.\n";
        $url = $this->apiUrl . 'areas/2077';
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    public function getCompetitions() { 
        $url = $this->apiUrl . 'competitions/';
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    public function getTeams($teamNbr) {
        $url = $this->apiUrl . "teams/$teamNbr/matches";
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    public function getPersons($personId) {
        $url = $this->apiUrl . "persons/$personId";
        $response = $this->makeRequest($url);
        $data = json_decode($response, true);
    
        if (is_array($data)) {
            return $data;
        } else {
            return [];
        }
    }

    public function getMatches() {
        $url = $this->apiUrl . 'matches/';
        $response = $this->makeRequest($url);
        return json_decode($response, true);
    }

    private function makeRequest($url) {
        $reqPrefs['http']['method'] = 'GET';
        $reqPrefs['http']['header'] = 'X-Auth-Token: ' . $this->apiKey;
        $streamContext = stream_context_create($reqPrefs);
        sleep(1);
        return file_get_contents($url, false, $streamContext);
    }
}
?>