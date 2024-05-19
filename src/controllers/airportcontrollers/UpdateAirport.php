<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class UpdateAirport
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
        $aircode = $_POST["AIRCODE"];

        $this->processResourceRequest($method, $id);

    }

    private function processResourceRequest(string $method, $id): void
    {
        $record = $this->gateway->getAirport($id);

        if ( ! $record) {
            http_response_code(404);
            echo json_encode(["message" => "Aircode not found"]);
            return;
        }

        switch ($method) {

            case "POST":
                $aircode = $_POST["AIRCODE"];
                $timezone = $_POST["TIME_ZONE"];
        
                $data = ["aircode" => $aircode, "time_zone" => $timezone];

                $errors = $this->getValidationErrors($data, false);
    
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->gateway->updateAirport($record, $data, $id);      
                http_response_code(201);          
                echo $this->twig->render("home.php", $data);
                break;
    
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE, POST");
        }

    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["id"])) {
            $errors[] = "id is required";
        }

        if (isset($data["kidsdriv"]) && $data["kidsdriv"] !== "") {
            if (filter_var($data["kidsdriv"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Kids Drive must be an integer";
            }
        }

        return $errors;
    }
}

