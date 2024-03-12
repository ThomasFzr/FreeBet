<?php
class RegisterController
{
    public function processRegister()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST["email"];
            $password = $_POST["password"];

            require_once __DIR__ . '/../models/Register.php';
            $register = new Register();
            if ($register->insertIntoTableRegister($email, $password)) {
                header('Location: /');
            } else {
                header('Location: /inscription');
            }
        }
    }
}
