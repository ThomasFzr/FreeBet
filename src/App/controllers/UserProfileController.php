<?php
// session_start();
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/User.php';
class UserProfileController
{
    private $userModel;
    private $twig;
    public function __construct()
    {
        $this->userModel = new User();
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new \Twig\Environment($loader);
    }
    public function showUserProfile()
    {
        if (isset($_SESSION['mail'])) { // Assurez-vous d'utiliser la clé correcte ici
            $userEmail = $_SESSION['mail']; // Utilisation de la clé correcte
            $userData = $this->userModel->getUserInfo($userEmail);
            if ($userData) {
                echo $this->twig->render('profile.twig', ['user' => $userData]);
            } else {
                echo "Erreur : Utilisateur non trouvé.";
            }
        } else {
            echo "Erreur : Vous devez être connecté pour voir cette page.";
        }
    }
}
$userProfile = new UserProfileController();
$userProfile->showUserProfile();
