<?php
// Include your database connection file here
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password before storing it for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username or email already taken. Please choose another one.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (name, username, email, password) VALUES ('$name', '$username', '$email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            // Store username in session
            $_SESSION['username'] = $username;
            // Redirect to additional information page
            header("Location: additional_info.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
