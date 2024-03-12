<?php
class GetInfoUserController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function getInfoUser()
    {
        if(isset($_SESSION['userID'])){
        require_once __DIR__ . '/../models/User.php';
        $user = new User();

        $infoUser = $user->getUserInfo($_SESSION['mail']);
        echo $this->twig->render('detailsAccountView.twig', ['infoUser' => $infoUser]);
        }else{
            header('Location: /connection');
        }
    }
}
