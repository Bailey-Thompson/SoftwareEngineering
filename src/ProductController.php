<?php

class ProductController
{
    private ProductGateway $gateway;

    public function __construct(ProductGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    public function processRequest(string $method, ?string $id): void
    {
        $urlParts = parse_url($_SERVER['REQUEST_URI']);
        $pathSegments = explode('/', trim($urlParts['path'], '/'));
        $id = end($pathSegments);

        if ($id) {
            $this->processResourceRequest($method, $id);
        } else {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id): void
    {
        $record = $this->gateway->get($id);

        if ( ! $record) {
            http_response_code(404);
            echo json_encode(["message" => "Record not found"]);
            return;
        }

        switch ($method) {
            case "GET":
                echo json_encode($record);
                break;

            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data, false);

                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->gateway->update($record, $data, $id);

                echo json_encode([
                    "message" => "$id Updated",
                ]);
                break;

            case "DELETE":
                $rows = $this->gateway->delete($id);

                echo json_encode([
                    "message" => "Product $id deleted",
                    "rows" => $rows
                ]);
                break;

            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }

    }

    private function processCollectionRequest(string $method): void
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

                $id = $this->gateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message" => "Product Created",
                    "id" => $id
                ]);
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