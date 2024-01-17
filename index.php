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
}
