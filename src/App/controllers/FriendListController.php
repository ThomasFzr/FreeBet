<?php

class FriendListController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function FriendList()
    {
        if (isset($_SESSION['userID'])) {
            require_once __DIR__ . '/../models/Friend.php';
            $friend = new Friend();
            // Récupérer les amis de l'utilisateur connecté
            $userID = $_SESSION['userID'];
            $friends = $friend->getFriendsOfUser($userID);
            // Rendre la vue avec la liste d'amis
            echo $this->twig->render(
                'friend/friendView.twig',
                [
                    'friends' => $friends
                ]
            );
        } else {
            header('Location: /connection');
        }
    }
}
