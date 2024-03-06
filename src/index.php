<?php
session_start();

require_once 'vendor/autoload.php';


spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $baseDir = __DIR__ . '/App/controllers/';
    $filePath = $baseDir . $file;
    if (file_exists($filePath)) {
        include $filePath;
    }
});

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/App/views');
$twig = new \Twig\Environment($loader);

if (isset($_SESSION['userID'])) {
    $isConnected = true;
    $twig->addGlobal('isConnected', $isConnected);
}

if (isset($_SESSION['surname'])) {
    $surname = $_SESSION['surname'];
    $twig->addGlobal('surname', $surname);
}

if (isset($_SESSION['isAdmin'])) {
    $isAdmin = $_SESSION['isAdmin'];
    $twig->addGlobal('isAdmin', $isAdmin);
}

if (isset($_SESSION['successMsg'])) {
    $successMsg = $_SESSION['successMsg'];
    $twig->addGlobal('successMsg', $successMsg);
    unset($_SESSION['successMsg']);
}

if (isset($_SESSION['errorMsg'])) {
    $errorMsg = $_SESSION['errorMsg'];
    $twig->addGlobal('errorMsg', $errorMsg);
    unset($_SESSION['errorMsg']);
}

if (isset($_GET['accommodationType'])) {
    $accommodationType = $_GET['accommodationType'];
    $twig->addGlobal('accommodationType', $accommodationType);
} else {
    $accommodationType = '';
    $twig->addGlobal('accommodationType', $accommodationType);
}

$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($urlPath, '/'));
$route = "/" . $parts[0];
$id = $parts[1] ?? null;

switch ($route) {
    case '':
    case '/':
        echo $twig->render('homeView.twig');
        break;

    case '/connection':
        echo $twig->render('connectionView.twig');
        break;

    case '/inscription':
        echo $twig->render('registerView.twig');
        break;

    case '/processLogin':
        $controller = new LoginController();
        $controller->processLogin();
        break;

    case '/processRegister':
        $controller = new RegisterController();
        $controller->processRegister();
        break;

    case '/myAccount':
        $controller = new GetInfoUserController($twig);
        $controller->getInfoUser();
        break;

    case '/addMatch':
        echo $twig->render('addMatchView.twig');
        break;

    case '/processAddMatch':
        $controller = new AddMatchController();
        $controller->AddMatch();
        break;

    case '/editInfoUser':
        $controller = new EditInfoUserController();
        $controller->processEditInfoUser();
        break;

    case '/fillFootData':
        $controller = new FootballController();
        $controller->getFootballMatches();
        break;

    case '/deconnection':
        $controller = new DeconnectionController();
        $controller->processDeconnection();
        break;
}
