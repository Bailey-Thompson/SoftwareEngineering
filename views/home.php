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

        body {
            margin: 0;
            background-color: #f2f2f2;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            height: 100vh;
            padding-top: 50px;
        }

        .button-row {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .large-button {
            display: inline-block;
            padding: 30px 60px;
            margin: 0 20px;
            width: 250px;
            font-size: 24px;
            text-align: center;
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 12px;
            cursor: pointer;
        }

        .large-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="#" onclick="redirectToHome()">Home</a>
</div>

<div class="main-container">
    <div class="button-row">
        <button  id="passenger" class="large-button">Passenger</button>
        <button  id="flight"class="large-button">Flight</button>
        <button  id="airplane"class="large-button">Airplane</button>
    </div>
    <div class="button-row">
        <button  id="city"class="large-button">City</button>
        <button  id="staff"class="large-button">Staff</button>
    </div>
</div>

<script>
    //navbar functions scripts
    function redirectToHome() {
        window.location.href = "/"
    }
    document.getElementById("passenger").addEventListener("click", function(){
        window.location.href = "/passenger";
    });
    document.getElementById("flight").addEventListener("click", function(){
        window.location.href = "/flight";
    });
    document.getElementById("airplane").addEventListener("click", function(){
        window.location.href = "/airplane";
    });
    document.getElementById("city").addEventListener("click", function(){
        window.location.href = "/city";
    });
    document.getElementById("staff").addEventListener("click", function(){
        window.location.href = "/staff";
    });
</script>

</body>
</html>
