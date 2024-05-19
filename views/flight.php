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
            width: 100%;
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
    <a href="#" onclick="redirectToNFlight()">New Flight</a>
    <a href="#" onclick="redirectToUFlight()">Update Flight</a>
    <a href="#" onclick="redirectToAStop()">Add Stop</a>
    <a href="#" onclick="redirectToVStop()">View Stops</a>
    <a href="#" onclick="redirectToAStaff()">Assign Staff</a>
    <a href="#" onclick="redirectToSStaff()">Search Staff</a>
    <a href="#" onclick="redirectToSPassengers()">Search Passengers</a>
</div>

<form action="/viewFlight" method="POST">
<input type="flightnum" id="FLIGHTNUM" name="FLIGHTNUM" placeholder="Flight Number">
<button type="submit">View</button>
<button type="submit" formaction="/deleteFlight">Delete</button>
</form>

<script>
    function redirectToHome() {
        window.location.href = "/"
    }
    function redirectToNFlight() {
        window.location.href = "/NewFlight"
    }
    function redirectToUFlight() {
        window.location.href = "/UpdateFlight"
    }
    function redirectToAStop() {
        window.location.href = "/AddStop"
    }
    function redirectToVStop() {
        window.location.href = "/ViewStop"
    }
    function redirectToAStaff() {
        window.location.href = "/AssignStaff"
    }
    function redirectToSStaff() {
        window.location.href = "/SStaff"
    }
    function redirectToSPassengers() {
        window.location.href = "/SPassengers"
    }
</script>

</body>
</html>
