<?php
// Include your database connection file here
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $goal = $_POST['goal'];
    $username = $_SESSION['username'];

    // Calculate BMI
    $height_m = $height / 100; // Convert height to meters
    $bmi = $weight / ($height_m * $height_m);

    // Update the user's additional information in the database
    $sql = "UPDATE users SET height='$height', weight='$weight', goal='$goal', bmi='$bmi' WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Information saved successfully. Your BMI is " . round($bmi, 2) . ". Redirecting to welcome page...</p>";
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'welcome.php';
                }, 5000); // 5 seconds delay
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>