<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class AddAirplane
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
        //fetches data from form and sets to variables
        $numser = $_POST["NUMSER"];
        $manufacturer = $_POST["MANUFACTURER"];
        $model = $_POST["MODEL"];
        $total_seats = $_POST["TOTAL_SEATS"];

        //creates an array $data
        $data = ["numser" => $numser, "manufacturer" => $manufacturer, "model" => $model, "total_seats" => $total_seats];

        //Passes the array into processResourceRequest
        $this->processResourceRequest($method, $data);

    }
    private function processResourceRequest(string $method, $data): void
    {
        {
            switch ($method) {
        
                case "POST":
        
                    if ( ! empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    
                    //carries out the createAirplane function found in ProductGateway
                    $id = $this->gateway->createAirplane($data);
                    http_response_code(201);
                    //Sends the user back to the homepage
                    echo $this->twig->render("home.php",$data);
                    break;
        
                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE, POST");
            }
        
        }

    }
}

