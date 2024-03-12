<?php
class EditInfoUserController
{
    public function processEditInfoUser()
    {
        if (isset($_SESSION['userID'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                require_once __DIR__ . '/../models/Database.php';
                $database = new Database();
                $successMsg = "";

                if (isset($_POST["mail"]) && $_POST["mail"] != '') {
                    $database->updateTable("User", "mail", $_POST["mail"], $_SESSION["mail"]);
                    $_SESSION["mail"] = $_POST["mail"];
                    $successMsg = " Mail";
                }
                if (isset($_POST["pwd"]) && $_POST["pwd"] != '') {
                    $hash = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
                    $database->updateTable("User", "pwd", $hash, $_SESSION["mail"]);
                    $successMsg = $successMsg . " MDP";
                }
                if (isset($_POST["name"]) && $_POST["name"] != '') {
                    $database->updateTable("User", "name", $_POST["name"], $_SESSION["mail"]);
                    $successMsg = $successMsg ."Nom";
                }
                if (isset($_POST["surname"]) && $_POST["surname"] != '') {
                    $database->updateTable("User", "surname", $_POST["surname"], $_SESSION["mail"]);
                    $successMsg = $successMsg . " Prénom";
                    $_SESSION['surname'] = $_POST["surname"];
                }
                if (isset($_POST["phoneNbr"]) && $_POST["phoneNbr"] != '') {
                    $database->updateTable("User", "phoneNbr", $_POST["phoneNbr"], $_SESSION["mail"]);
                    $successMsg = $successMsg . " Numéro de téléphone";
                }
               
                if ((isset($_POST["name"]) && $_POST["name"] != '') || (isset($_POST["surname"]) && $_POST["surname"] != '')
                    || (isset($_POST["phoneNbr"]) && $_POST["phoneNbr"] != '') || (isset($_POST["mail"]) && $_POST["mail"] != '')
                    || (isset($_POST["pwd"]) && $_POST["pwd"] != '')
                ) {
                    $_SESSION['successMsg'] = $successMsg . " changé avec succès";
                }

                header('Location: /myAccount');
            } else {
                echo "erreur edit info user";
            }
        } else {
            header('Location: /connection');
        }
    }
}
