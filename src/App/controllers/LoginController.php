<?php
class LoginController
{
    public function processLogin()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            require_once __DIR__ . '/../models/Connection.php';
            $connection = new Connection();
            if ($connection->authenticateUser($email, $password)) {
                $_SESSION['successMsg'] = "Connexion réussie!";
                header('Location: /');
                exit;
            } else {
                $_SESSION['errorMsg'] = "Mail ou mot de passe invalide!";
                header('Location: /connection');
                exit;
            }
        } else {
        }
    }
}
