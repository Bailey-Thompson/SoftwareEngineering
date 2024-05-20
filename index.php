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

$request = $_SERVER['REQUEST_URI'];
//file path
$viewDir = '/views/';
//switch for url
switch ($request) {
    case '':
    case '/':
        header('Content-Type: text/html');
        require __DIR__ . $viewDir . 'home.php';
        break;
    case '/city':
        //views + city.php
        require __DIR__ . $viewDir . 'city.php';
        break;
    //NewAirport case, Allows the user to add a new airport to the database
    case '/NewAirport':
        //loads newairport.twig which is the form for creating a new airport
        require_once(__DIR__ . '/views/airport/newairport.twig');
        break;
    //UpdateAirport case, Allows the user to update an airport already in the database
    case '/UpdateAirport':
        //loads updateairport.twig which is the form for updating an airport
        require_once(__DIR__ . '/views/airport/updateairport.twig');
        break;
    //createAirport case, carried out after submitting the form
    case '/createAirport':
        //Takes the user to /AddAirport.php
        require __DIR__ . '/src/controllers/airportcontrollers/AddAirport.php';
        //Creates an instance of the AddAiport controller
        $controller = new AddAirport($gateway, $twig);
        //Process the Request passing in the current method (POST) and the Aircode
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    //createUpdate case, carried out after submitting the form
    case '/updateAirport':
        //Takes the user to /UpdateAirport.php
        require __DIR__ . '/src/controllers/airportcontrollers/UpdateAirport.php';
        //Creates an instance of the UpdateAirport controller
        $controller = new UpdateAirport($gateway, $twig);
        //Process the Request passing in the current method (POST) and the Aircode
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    //viewAirport case
    case '/viewAirport':
        //Loads ViewAirport.php
        require __DIR__ . '/src/controllers/airportcontrollers/ViewAirport.php';
        //Creates an instance of the ViewAirport controller
        $controller = new ViewAirport($gateway, $twig);
        //Process the Request passing in the current method (POST) and the Aircode
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['AIRCODE']);
        break;
    //deleteAirport case
    case '/deleteAirport':
        //Loads the DeleteAirport.php
        require __DIR__ . '/src/controllers/airportcontrollers/DeleteAirport.php';
        //Creates an instance of the DeleteAirport controller
        $controller = new DeleteAirport($gateway, $twig);
        //Process the Request passing in the current method (POST) and the Aircode
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
    case '/manageStaff':
        require __DIR__ . '/src/controllers/flightcontrollers/ManageStaff.php';
        $controller = new AddStaff($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
        break;
    case '/SStaff':
        require_once(__DIR__ . '/views/displayStaff.php');
        break;
    case '/loadStaff':
        require __DIR__ . '/src/controllers/flightcontrollers/ViewStaff.php';
        $controller = new ViewStaff($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
        break;
    case '/SPassengers':
        require_once(__DIR__.'/views/displayPassengers.php');
        break;
    case '/loadPassengers':
        require __DIR__ . '/src/controllers/flightcontrollers/ViewPassengers.php';
        $controller = new ViewPassengers($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['FLIGHTNUM']);
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
        require_once(__DIR__ . '/views/passenger/newpassenger.twig');
        break;
    case '/UpdatePassenger':
        require_once(__DIR__ . '/views/passenger/updatepassenger.twig');
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
    case '/BookFlight':
        require_once(__DIR__ . '/views/passenger/bookflight.twig');
        break;
    case '/createBooking':
        require __DIR__ . '/src/controllers/passengercontrollers/CreateBooking.php';
        $controller = new AddBooking($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    case '/SearchBookings':
        require_once(__DIR__ . '/views/booking.php');
        break;
    case '/loadBooking':
        require __DIR__ . '/src/controllers/passengercontrollers/ViewBooking.php';
        $controller = new ViewBooking($gateway, $twig);
        $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['PASSNUM']);
        break;
    case '/staff':
        require __DIR__ . $viewDir . 'staff.php';
        break;
        case '/NewEmployee':
            require_once(__DIR__ . '/views/staff/newemployee.twig');
            break;
        case '/UpdateEmployee':
            require_once(__DIR__ . '/views/staff/updateemployee.twig');
            break;
        case '/createEmployee':
            require __DIR__ . '/src/controllers/employeecontrollers/AddEmployee.php';
            $controller = new AddEmployee($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
        case '/updateEmployee':
            require __DIR__ . '/src/controllers/employeecontrollers/UpdateEmployee.php';
            $controller = new UpdateEmployee($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
        case '/viewEmployee':
            require __DIR__ . '/src/controllers/employeecontrollers/ViewEmployee.php';
            $controller = new ViewEmployee($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
        case '/deleteEmployee':
            require __DIR__ . '/src/controllers/employeecontrollers/DeleteEmployee.php';
            $controller = new DeleteEmployee($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
        case '/ViewFlights':
            require_once(__DIR__.'/views/displayAFlights.php');
            break;
        case '/loadAFlights':
            require __DIR__ . '/src/controllers/employeecontrollers/ViewFlights.php';
            $controller = new ViewFlights($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
        case '/AddRating':
            require_once(__DIR__ . '/views/staff/newpilot.twig');
            break;
        case '/createPilot':
            require __DIR__ . '/src/controllers/employeecontrollers/CreatePilot.php';
            $controller = new AddPilot($gateway, $twig);
            $controller->processRequest($_SERVER['REQUEST_METHOD'], $_POST['EMPNUM']);
            break;
    default:
        http_response_code(404);
    }