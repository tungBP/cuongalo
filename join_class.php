<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include('config.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $class_id = $_POST['class_id'];

    // Check if the user is already enrolled in the class
    $sql = "SELECT * FROM enrollments WHERE username='$username' AND class_id='$class_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $message = "You are already enrolled in this class.";
    } else {
        // Enroll the user in the class
        $sql = "INSERT INTO enrollments (username, class_id) VALUES ('$username', '$class_id')";
        if ($conn->query($sql) === TRUE) {
            $message = "Successfully joined the class.";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }

        main {
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 120px);
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message {
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #e0ffe0;
            border: 1px solid #00a000;
            color: #007000;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Class Enrollment</h1>
    </header>
    <main>
        <div class="container">
            <?php
            if (!empty($message)) {
                echo "<p class='message'>$message</p>";
            }
            ?>
            <p><a href="classes.php" class="btn">Back to Classes</a></p>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gym Website. All rights reserved.</p>
    </footer>
</body>
</html>
