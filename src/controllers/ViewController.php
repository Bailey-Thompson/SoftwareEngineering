<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views'));

class ViewController
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
        $method = $_SERVER["REQUEST_METHOD"];
        $id = $_POST["ID"];
        $this->processResourceRequest($method, $id);
    }

    private function processResourceRequest(string $method, ?string $id): void
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
            <style>
                .navbar {
                    background-color: #4CAF50;
                    overflow: hidden;
                }
                
                .navbar a {
                    float: left;
                    display: block;
                    color: #f2f2f2;
                    text-align: center;
                    padding: 14px 20px;
                    text-decoration: none;
                }
                
                .navbar a:hover {
                    background-color: #ddd;
                    color: black;
                }
                input {
                    text-align: center;
                    width: 100%;
                    padding: 12px 20px;
                    margin-bottom: 10px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }
                .error-container {
                    text-align: center;
                    width: 40%;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                    color: red;
                }
                form {
                    text-align: center;
                    width: 40%;
                    margin: 20px auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                }
            </style>
        </head>
        <body>

        <div class="navbar">
            <a href="#" onclick="redirectToHome()">Home</a>
            <a href="#" onclick="redirectToAdd()">Add New Record</a>
            <a href="#" onclick="redirectToAll()">View All Records</a>
            <a href="#" onclick="redirectToUpdate()">Update Record</a>
            <a href="#" onclick="redirectToGraphs()">View Graphs</a>
        </div>

        <?php
        if ($id === null || $id === "") {
            echo '<div class="error-container">ID is required.</div>';
        } else {
            switch ($method) {
                case "POST":
                    $data = $this->gateway->get($id);
                    if ($data === null) {
                        echo '<div class="error-container">ID does not exist.</div>';
                    } else {
                        echo $this->twig->render("view.twig", $data);
                    }
                    break;

                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE");
            }
        }
        ?>

        <script>
            function redirectToAdd() {
                window.location.href = "/add";
            }
            function redirectToAll() {
                window.location.href = "/all";
            }
            function redirectToUpdate() {
                window.location.href = "/change";
            }
            function redirectToHome() {
                window.location.href = "/";
            }
            function redirectToGraphs() {
                window.location.href = "/graphs";
            }
        </script>

        </body>
        </html>
        <?php
    }
}