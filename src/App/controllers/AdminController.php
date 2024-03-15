<?php
class AdminController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }
    public function AdminController()
    {
        if (isset($_SESSION['isAdmin'])) {
        echo $this->twig->render('admin/adminView.twig');
    }
    }
}
