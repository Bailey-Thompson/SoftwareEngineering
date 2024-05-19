<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class UpdatePassenger
{
    private ProductGateway $gateway;
    private \Twig\Environment $twig;

    public function __construct(ProductGateway $gateway, \Twig\Environment $twig)
    {
        $this->gateway = $gateway;
        $this->twig = $twig;
    }
    public function processRequest(string $method, ?string $id): void
    {
        $id = $_POST["PASSNUM"];

        $this->processResourceRequest($method, $id);

    }

    private function processResourceRequest(string $method, $id): void
    {
        $record = $this->gateway->getPassenger($id);

        if ( ! $record) {
            http_response_code(404);
            echo json_encode(["message" => "Passenger Number not found"]);
            return;
        }

        switch ($method) {

            case "POST":
                $passnum = $_POST["PASSNUM"];
                $surname = $_POST["SURNAME"];
                $forename = $_POST["FORENAME"];
                $address = $_POST["ADDRESS"];
                $phone = $_POST["PHONE"];

                $data = ["passnum" => $passnum, "surname" => $surname, "forename" => $forename, "address" => $address, "phone" => $phone];

                $rows = $this->gateway->updatePassenger($record, $data, $id);      
                http_response_code(201);          
                echo $this->twig->render("home.php", $data);
                break;
    
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE, POST");
        }

    }
}

