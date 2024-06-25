<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Include your database connection file here
include('config.php');

// SQL query to fetch all classes
$sql = "SELECT * FROM classes";
$result = $conn->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

// Check for SQL errors
if ($conn->error) {
    echo json_encode(["error" => $conn->error]);
    exit();
}

// Set the content type to JSON
header('Content-Type: application/json');

// Return the classes data as a JSON object
echo json_encode($classes, JSON_PRETTY_PRINT);
?>
