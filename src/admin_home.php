<?php

// Start session to access the user
session_start();

// Checks that user is logged in and is an admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] != 1) {
    // If not logged in then redirect back to login
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
    // Unset all sessions variables
    $_SESSION = array();

    // Destroy session
    session_destroy();

    // Redirect to the login page 
    header("Location: index.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>

    <!-- Admin page CSS -->
    <style>

        .body{
            margin: 0%;
        }
        
        .navbar {
            overflow: hidden;
            background-color: #333;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Style the active/current link */
        .navbar a.active {
            background-color: #4CAF50;
            color: white;
        }

        /* Logout button styles */
        .logout-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>


</head>

<body>

    <!-- Navigation bar -->
        <div class="navbar">
            <a class="active" href="#home">Home</a>
            <a href="staff.php">Staff</a>
            <a href="#Roster">Roster</a>
            <a href="#about">About</a>
            <a href="#settings">Settings</a>
        </div>


    <h2>Welcome, Admin!</h2>
    <p>This is the admin home page.</p>

    
    <!-- Log out button  -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <button class="logout-button" type="submit" name="logout">Log out</button>
    </form>

    
</body>

</html>