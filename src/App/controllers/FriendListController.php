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
        if (isset($_SESSION['isConnected'])) {
            require_once __DIR__ . '/../models/Friend.php';
            $friend = new Friend();
            $friends = $friend->getFriendsOfUser();
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
