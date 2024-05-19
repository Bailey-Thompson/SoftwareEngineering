<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/views'));


spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler(array("ErrorHandler", "handleError"));
set_exception_handler("ErrorHandler::handleException");

$database = new Database("localhost", "airline", "root", "Datto@2004");

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';
switch ($request) {
    // case '/view':
    //     require __DIR__ . '/src/controllers/ViewController.php';
    //     $controller = new ViewController($gateway, $twig);
    //     $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['ID']);
    //     break;
    // case '/create':
    //     require __DIR__ . '/src/controllers/AddController.php';
    //     $controller = new AddController($gateway, $twig);
    //     $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['ID']);
    //     break;
    // case '/update':
    //     require __DIR__ . '/src/controllers/UpdateController.php';
    //     $controller = new UpdateController($gateway, $twig);
    //     $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['ID']);
    //     break;
    // case '/delete':
    //     require __DIR__ . '/src/controllers/DeleteController.php';
    //     $controller = new DeleteController($gateway, $twig);
    //     $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['ID']);
    //     break;
    // case '/add':
    //     require __DIR__ . $viewDir . 'add.twig';
    //     break;
    // case '/all':
    //     require __DIR__ . '/src/controllers/ViewAllController.php';
    //     $controller = new ViewAllController($gateway, $twig);
    //     $controller->processRequest($_SERVER['REQUEST_METHOD'], null);
    //     break;
    // case '/change':
    //     require __DIR__ . $viewDir . 'update.twig';
    //     break;
    case '':
    case '/':
        header('Content-Type: text/html');
        require __DIR__ . $viewDir . 'home.php';
        break;
    case '/city':
        require __DIR__ . $viewDir . 'city.php';
        break;
    case '/NewAirport':
        require_once(__DIR__ . '/views/airport/newairport.twig');
        break;
    case '/UpdateAirport';
        require_once(__DIR__ . '/views/airport/updateairport.twig');
        break;
    case '/createAirport':
        require __DIR__ . '/src/controllers/airportcontrollers/AddAirport.php';
        $controller = new AddAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    default:
        http_response_code(404);
    }