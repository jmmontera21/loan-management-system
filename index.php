<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = null;

    // Check if the email belongs to an admin or employee
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a new session
            $_SESSION['email'] = $email;
            $_SESSION['type'] = $row['user_type']; // Store user type in session

            if ($row['user_type'] == 'admin') {
                header("Location: admin_home.php");
            } elseif ($row['user_type'] == 'employee') {
                header("Location: employee_home.php");
            } else {
            $stmt->close();
            $conns->close();
            }
        } else {
            $stmx = $conns->prepare("SELECT * FROM customer WHERE email = ?");
            $stmx->bind_param("s", $email);
            $stmx->execute();
            $result = $stmx->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['email'] = $email;
                    header("Location: customer_page.php");
                } else {
                    // Incorrect password
                    $error = "Invalid password";
                }
            } else {
                // User or customer not found
                $error = "Account not found";
            }
            $conns->close();
        }
    }
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <div class="logo">
                <img src="img/LMS logo.png" alt="Loan Logo">
            </div>
            <h1>WELCOME</h1>
            <p>Login to continue</p>
            <form action="index.php" method="POST">
                <label for="email">Email<span>*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                
                <label for="password">Password<span>*</span></label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span style="display:flex; justify-content:center">
                <button type="submit">LOGIN</button>
                <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
                </span>
            </form>
        </div>
    </div>
</body>
</html>
