<?php
$host = "db";
$port = "3306";
$user = "admin";
$password = "admin";
$database = "aged_care";

// Create mysqli connection
$conn = new mysqli($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Init the error message
$error_message = "";

// Check forms are submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrive name and password
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Regex pattern for username check
    $username_pattern = '/^[a-zA-Z][a-zA-Z0-9_]*$/';

    if (!preg_match($username_pattern, $username)) {
        $error_message = "Username must start with a letter and contain no numeric number";
    } else {
        // SQL statement to retrive username
        $sql = "SELECT * FROM Staff WHERE Name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check username exists in the table
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            $actual_pass = $row["PasswordHash"];

            // Verify password
            // if ($password === $actual_pass) 

            $hashedPWD = password_hash($actual_pass, PASSWORD_DEFAULT);
            if (password_verify($password, $hashedPWD)) {

                // Start the session and set user role
                session_start();
                $_SESSION["role"] = $row["RoleId"];

                // Redirect users based on their roles
                switch ($_SESSION["role"]) {
                    case 1:
                        header("Location: admin_home.php");
                        break;
                    case 2:
                        header("Location: staff_home.php");
                        break;
                    case 3:
                        header("Location: cleaner_home.php");
                        break;
                    case 4:
                        header("Location: accountant_home.php");
                        break;
                    default:
                        echo "Invalid role";
                }
                exit;
            } else {
                $error_message = "Incorrect Password";
            }
        } else {
            $error_message = "User not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        .error-message {
            color: red;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            background-color: #4caf50;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<!-- <body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body> -->

<body>


    <div class="login-container">
        <h2>Aged care login system</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </div>

    <!-- Create error message -->
    <div class="error-message"><?php echo $error_message; ?></div>


</body>

</html>