<?php
include 'db_connect.php';
// Start the session (if you are using sessions)
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the form data
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Hash the password for security (using bcrypt)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Debug: Print hashed password to confirm it's being hashed correctly
    echo "Hashed Password: " . $hashed_password . "<br>";

    // Insert the new customer into the `accounts` table
    $sql = "INSERT INTO accounts (email, password) 
            VALUES ('$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to a success page or login page after successful signup
        echo "New account created successfully. <a href='customer_login.php'>Log in</a>";
    } else {
        // Display an error message if something goes wrong
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/customer(1).css">
</head>
<body>
        <header>
        <nav>
            <div class="logo">
                <img src="img/LMS logo.png" alt="Logo">
            </div>
        </nav>
        </header>

        <!-- Middle Section (Form) -->
        <div class="middle-section">
            <div class="form-container">
                <h1>Sign Up</h1>
                <form action="customer_signup.php" method="post">
                    <div class="form-group">
                        <label for="email">Email <span>*</span></label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password <span>*</span></label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group terms">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I agree to the <a href="#">Privacy Policy</a> and <a href="#">Software Services Agreement</a>
                        </label>
                    </div>
                    <button type="submit" class="btn-create-account">Create Account</button>
                </form>
                <p class="login-link">Already have an account? <a href="customer_login.php">Log In</a></p>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <!-- You can add more content here if needed -->
        </div>
</body>
</html>
