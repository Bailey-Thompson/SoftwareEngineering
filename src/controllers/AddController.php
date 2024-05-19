<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views'));

class AddController
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
        $id = $_POST["ID"];
        $kidsdriv = $_POST["KIDSDRIV"];
        $age = $_POST["AGE"];
        $income = $_POST["INCOME"];
        $mstatus = $_POST["MSTATUS"];
        $gender = $_POST["GENDER"];
        $education = $_POST["EDUCATION"];
        $occupation = $_POST["OCCUPATION"];
        $car_type = $_POST["CAR_TYPE"];
        $red_car = $_POST["RED_CAR"];
        $car_age = $_POST["CAR_AGE"];
        $claim_flag = $_POST["CLAIM_FLAG"];
        $clm_amt = $_POST["CLM_AMT"];
        $clm_freq = $_POST["CLM_FREQ"];
        $oldclaim = $_POST["OLDCLAIM"];
        $method = $_SERVER["REQUEST_METHOD"];

        $data = ["id" => $id, "age" => $age, "kidsdriv"=> $kidsdriv, "income" => $income, "mstatus" => $mstatus, "gender" => $gender, "education" => $education, "occupation" => $occupation, "car_type" => $car_type, "red_car" => $red_car, "car_age" => $car_age, "claim_flag" => $claim_flag, "clm_amt" => $clm_amt, "clm_freq" => $clm_freq, "oldclaim" => $oldclaim];

        if ($id) {
            $this->processResourceRequest($method, $data);
        } else {
            $this->processCollectionRequest($method);
        }
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
        
                    $id = $this->gateway->create($data);
                    http_response_code(201);
                    echo $this->twig->render("home.php",$data);
                    break;
        
                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE, POST");
            }
        
        }

    }

    private function processCollectionRequest(string $method)
    {   
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $this->gateway->create($data);
                echo $this->twig->render("view.twig", $data);

                http_response_code(201);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, POST");
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

