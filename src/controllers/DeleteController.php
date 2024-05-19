<?php

require_once __DIR__ . '/../../vendor/autoload.php'; 

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views'));

class DeleteController
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
        $method = $_SERVER["REQUEST_METHOD"];

        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }


    private function processResourceRequest(string $method, string $id): void
    {
        switch ($method) {

            case "POST":
                $data = $this->gateway->delete($id);
                echo $this->twig->render("home.php");
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }

    }

    private function processCollectionRequest(string $method)
    {   
        switch ($method) {
            case "GET":
                echo json_encode($this->gateway->getAll());
                break;

            case "POST":
                echo json_encode($this->gateway->getAll());
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
            $errors[] = "id is required";
        }

        if (array_key_exists("kidsdriv", $data)) {
            if (filter_var($data["kidsdriv"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "Kids Drive must be an integer";
            }
        }

        return $errors;
    }
}

