<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class DeletePassenger
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
                $data = $this->gateway->deletePassenger($id);
                echo $this->twig->render("home.php");
                break;
    
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE, POST");
        }

    }

}

