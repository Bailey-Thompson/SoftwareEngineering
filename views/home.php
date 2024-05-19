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
    <a href="#" onclick="redirectToAdd()">Add New Record</a>
    <a href="#" onclick="redirectToAll()">View All Records</a>
    <a href="#" onclick="redirectToUpdate()">Update Record</a>
    <a href="#" onclick="redirectToGraphs()">View Graphs</a>
</div>

<form action="/view" method="POST">
<input type="number" id="ID" name="ID" placeholder="ID">
<button type="submit">View</button>
<button type="submit" formaction="/delete">Delete</button>
</form>

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
        window.location.href = "/"
    }
    function redirectToGraphs() {
        window.location.href = "/graphs";
    }
</script>

</body>
</html>
