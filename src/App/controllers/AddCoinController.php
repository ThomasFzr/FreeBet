<?php
require_once __DIR__ . '/../models/Bet.php';
class AddCoinController
{
    public function AddCoin()
    {
        // Assuming you have already instantiated the database connection somewhere in your code
        $db = new Database();
        $conn = $db->conn;

        // Pass the database connection to the Bet class constructor
        $bet = new Bet($conn);

        // Ensure that $_POST['totalBetAmount'] is set before accessing it
        if (isset($_POST['totalBetAmount'])) {
            // Call the UpdateCoinFromBet method with the necessary arguments
            $userID = $_SESSION['userID'];
            $totalBetAmount = $_POST['totalBetAmount'];
            $bet->UpdateCoinFromBet($userID, $totalBetAmount);
        } else {
            // Handle the case where $_POST['totalBetAmount'] is not set
            // For example, display an error message or redirect the user
            // to a page where they can submit the form again.
        }
    }
}
