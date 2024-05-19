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

//$controller = new ProductController($gateway);

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
        $controller = new DeleteAirport($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    case '/airplane':
        require __DIR__ . $viewDir . 'airplane.php';
        break;
    case '/NewAirplane':
        require_once(__DIR__ . '/views/airplane/newairplane.twig');
        break;
    case '/UpdateAirplane':
        require_once(__DIR__ . '/views/airplane/updateairplane.twig');
        break;
    case '/createAirplane':
        require __DIR__ . '/src/controllers/airplanecontrollers/AddAirplane.php';
        $controller = new AddAirplane($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['NUMSER']);
        break;
    case '/updateAirplane':
        require __DIR__ . '/src/controllers/airplanecontrollers/UpdateAirplane.php';
        $controller = new UpdateAirplane($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['NUMSER']);
        break;
    case '/viewAirplane':
        require __DIR__ . '/src/controllers/airplanecontrollers/ViewAirplane.php';
        $controller = new ViewAirplane($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['NUMSER']);
        break;
    case '/deleteAirplane':
        require __DIR__ . '/src/controllers/airplanecontrollers/DeleteAirplane.php';
        $controller = new DeleteAirplane($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['NUMSER']);
        break;
    case '/flight':
        require __DIR__ . $viewDir . 'flight.php';
        break;
    case '/NewFlight':
        require_once(__DIR__ . '/views/flight/newflight.twig');
        break;
    case '/UpdateFlight':
        require_once(__DIR__ . '/views/flight/updateflight.twig');
        break;
    case '/AddStop':
        require_once(__DIR__ . '/views/flight/addstop.twig');
        break;
    case '/ViewStop':
        require_once(__DIR__ . '/views/stop.php');
        break;
    case '/loadStop':
        require __DIR__ . '/src/controllers/flightcontrollers/ViewStop.php';
        $controller = new ViewStop($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/AssignStaff':
        require_once(__DIR__ . '/views/flight/assignstaff.twig');
        break;
    case '/SearchStaff':
        require_once(__DIR__ . '/views/flight/searchstaff.twig');
        break;
    case '/SearchPassengers':
        require_once(__DIR__.'/views/flight.searchpassengers.twig');
        break;
    case '/createFlight':
        require __DIR__ . '/src/controllers/flightcontrollers/AddFlight.php';
        $controller = new AddFlight($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/updateFlight':
        require __DIR__ . '/src/controllers/flightcontrollers/UpdateFlight.php';
        $controller = new UpdateFlight($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/viewFlight':
        require __DIR__ . '/src/controllers/flightcontrollers/ViewFlight.php';
        $controller = new ViewFlight($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/deleteFlight':
        require __DIR__ . '/src/controllers/flightcontrollers/DeleteFlight.php';
        $controller = new DeleteFlight($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/createStop':
        require __DIR__ . '/src/controllers/flightcontrollers/AddStop.php';
        $controller = new AddStop($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/passenger':
        require __DIR__ . $viewDir . 'passenger.php';
        break;
    case '/NewPassenger':
        require_once(__DIR__ . '/views/airplane/newpassenger.twig');
        break;
    case '/UpdatePassenger':
        require_once(__DIR__ . '/views/airplane/updatepassenger.twig');
        break;
    case '/createPassenger':
        require __DIR__ . '/src/controllers/passengercontrollers/AddPassenger.php';
        $controller = new AddPassenger($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    case '/updatePassenger':
        require __DIR__ . '/src/controllers/passengercontrollers/UpdatePassenger.php';
        $controller = new UpdatePassenger($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    case '/viewPassenger':
        require __DIR__ . '/src/controllers/passengercontrollers/ViewPassenger.php';
        $controller = new ViewPassenger($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    case '/deletePassenger':
        require __DIR__ . '/src/controllers/passengercontrollers/DeletePassenger.php';
        $controller = new DeletePassenger($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    default:
        http_response_code(404);
    }