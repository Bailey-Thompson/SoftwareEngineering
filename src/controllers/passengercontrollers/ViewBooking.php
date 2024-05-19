<?php

require_once __DIR__ . '/../../../vendor/autoload.php'; // Include Composer's autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__);

$twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));

class ViewBooking
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
        $passnum = $_POST["PASSNUM"];

        $this->processResourceRequest($method, $passnum);

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
        <a href="#" onclick="redirectToNPassenger()">New Passenger</a>
        <a href="#" onclick="redirectToUPassenger()">Update Passenger</a>
        <a href="#" onclick="redirectToBFlight()">Book Flight</a>
        <a href="#" onclick="redirectToSBookings()">Search Bookings</a>
        </div>

        <?php
        if ($id === null || $id === "") {
            echo '<div class="error-container">Passenger Number is required.</div>';
        } else {
            switch ($method) {
                case "POST":
                    $data = $this->gateway->getBooking($id);
                    if ($data === null) {
                        echo '<div class="error-container">Passenger Number does not exist.</div>';
                    } else {
                        echo $this->twig->render("passenger/viewbooking.twig", ["data" => $data]);
                    }
                    break;

                default:
                    http_response_code(405);
                    header("Allow: GET, PATCH, DELETE");
            }
        }
        ?>

        <script>
        function redirectToHome() {
            window.location.href = "/"
        }
        function redirectToNPassenger() {
            window.location.href = "/NewPassenger"
        }
        function redirectToUPassenger() {
            window.location.href = "/UpdatePassenger"
        }
        function redirectToBFlight() {
            window.location.href = "/BookFlight"
        }
        function redirectToSBookings() {
            window.location.href = "/SearchBookings"
        }
        </script>

        </body>
        </html>
        <?php
    }
}