<?php
include("db.php");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM customers WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email is already registered!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO customers (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            if (mysqli_query($conn, $query)) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Alpha Canteen</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 370px;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .register-box input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .register-box button {
            width: 100%;
            padding: 12px;
            background: #ff3838;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .register-box button:hover {
            background: #e32e2e;
        }

        .error, .success {
            text-align: center;
            margin-bottom: 10px;
        }

        .error { color: red; }
        .success { color: green; }

        .register-box .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-box .login-link a {
            color: #ff3838;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>
    <?php
        if (!empty($error)) echo "<div class='error'>$error</div>";
        if (!empty($success)) echo "<div class='success'>$success</div>";
    ?>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </form>
</div>

</body>
</html>
