<?php

class AddFriendController
{
    private $Friend;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Friend.php';
        $this->Friend = new Friend();
    }

    public function addFriend()
    {
        if (isset($_SESSION['userID'])) {
            if (isset($_POST['friend_email'])) {

                // Ajouter l'ami en utilisant l'adresse e-mail
                $result = $this->Friend->addFriendByEmail($_POST['friend_email']);

                if ($result) {
                    $_SESSION['successMsg'] = "Ami ajouté avec succès.";
                } else {
                    $_SESSION['errorMsg'] = "Aucun utilisateur trouvé avec cette adresse e-mail.";
                }
            } else {
                $_SESSION['errorMsg'] = "Veuillez fournir une adresse e-mail pour ajouter un ami.";
            }
        } else {
            $_SESSION['errorMsg'] = "Vous devez être connecté pour ajouter un ami.";
        }

        header('Location: /friend');
    }
}
