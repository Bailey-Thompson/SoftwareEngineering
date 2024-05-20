<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class UpdateAirplane
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
        $numser = $_POST["NUMSER"];

        $this->processResourceRequest($method, $id);

    }

    private function processResourceRequest(string $method, $id): void
    {
        $record = $this->gateway->getAirplane($id);

        if ( ! $record) {
            http_response_code(404);
            echo json_encode(["message" => "Serial Number not found"]);
            return;
        }

        switch ($method) {

            case "POST":
                $numser = $_POST["NUMSER"];
                $manufacturer = $_POST["MANUFACTURER"];
                $model = $_POST["MODEL"];
                $totalseats = $_POST["TOTAL_SEATS"];
        
                $data = ["numser" => $numser, "manufacturer" => $manufacturer, "model" => $model, "total_seats" => $totalseats];
    
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->gateway->updateAirplane($record, $data, $id);      
                http_response_code(201);          
                echo $this->twig->render("home.php", $data);
                break;
    
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE, POST");
        }

    }
}

