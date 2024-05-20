<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class AddFlight
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

        $flightnum = $_POST["FLIGHTNUM"];
        $origin = $_POST["ORIGIN_AIRCODE"];
        $destination = $_POST["DESTINATION_AIRCODE"];
        $deptime = $_POST["DEPT_TIME"];
        $depdate = $_POST["DEPT_DATE"];
        $arrtime = $_POST["ARR_TIME"];
        $arrdate = $_POST["ARR_DATE"];
        $numser = $_POST["NUMSER"];

        $data = ["flightnum" => $flightnum, "origin_aircode" => $origin, "destination_aircode" => $destination, "dept_time" => $deptime, "dept_date" => $depdate, "arr_time" => $arrtime, "arr_date" => $arrdate,  "numser" => $numser];

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
        
                    $id = $this->gateway->createFlight($data);
                    http_response_code(201);
                    echo $this->twig->render("home.php",$data);
                    break;
        
                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE, POST");
            }
        
        }

    }
}

