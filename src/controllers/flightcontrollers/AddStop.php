<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class AddStop
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
        $aircode = $_POST["AIRCODE"];
        $stoporder = $_POST["STOP_ORDER"];
        $deptime = $_POST["DEPT_TIME"];
        $depdate = $_POST["DEPT_DATE"];
        $arrtime = $_POST["ARR_TIME"];
        $arrdate = $_POST["ARR_DATE"];

        $data = ["flightnum" => $flightnum, "aircode" => $aircode, "stop_order" => $stoporder, "dept_time" => $deptime, "dept_date" => $depdate, "arr_time" => $arrtime, "arr_date" => $arrdate];

        $this->processResourceRequest($method, $data);

    }

    private function processResourceRequest(string $method, $data): void
    {
        {
            switch ($method) {
        
                case "POST":
                    $errors = $this->getValidationErrors($data, false);
        
                    if ( ! empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
        
                    $id = $this->gateway->createStop($data);
                    http_response_code(201);
                    echo $this->twig->render("home.php",$data);
                    break;
        
                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE, POST");
            }
        
        }

    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["id"])) {
            $errors[] = "ID is required";
        }

        if (isset($data["kidsdriv"]) && $data["kidsdriv"] !== "") {
            if (filter_var($data["kidsdriv"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Kids Drive must be an integer";
            }
        }

        return $errors;
    }
}

