<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class AddStaff
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

        $empnum = $_POST["EMPNUM"];
        $flightnum = $_POST["FLIGHTNUM"];

        $data = ["empnum" => $empnum, "flightnum" => $flightnum];

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
        
                    $id = $this->gateway->addStaff($data);
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

