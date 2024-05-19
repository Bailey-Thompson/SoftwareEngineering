<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views'));

class ViewAllController
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
        switch ($method) {
            case "GET":
                $this->processCollectionRequest();
                break;
            case "POST":
                $this->processCollectionRequest();
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
                break;
        }
    }

    private function processCollectionRequest()
    {   
        $data = $this->gateway->getAll();
        echo $this->twig->render("viewAll.twig", ["data" => $data]);
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

