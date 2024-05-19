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
    case '/UpdateAirport':
        require_once(__DIR__ . '/views/airport/updateairport.twig');
        break;
    case '/createAirport':
        require __DIR__ . '/src/controllers/airportcontrollers/AddAirport.php';
        $controller = new AddAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    case '/updateAirport':
        require __DIR__ . '/src/controllers/airportcontrollers/UpdateAirport.php';
        $controller = new UpdateAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    case '/viewAirport':
        require __DIR__ . '/src/controllers/airportcontrollers/ViewAirport.php';
        $controller = new ViewAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    case '/deleteAirport':
        require __DIR__ . '/src/controllers/airportcontrollers/DeleteAirport.php';
        $controller = new UpdateAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    default:
        http_response_code(404);
    }