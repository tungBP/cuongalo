<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Include your database connection file here
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $class_id = $_POST['class_id'];

    // Delete the enrollment
    $sql = "DELETE FROM enrollments WHERE username='$username' AND class_id='$class_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Successfully canceled the class.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the schedules page
    header("Location: my_schedules.php");
    exit();
}
?>
