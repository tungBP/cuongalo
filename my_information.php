<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Include your database connection file here
include('config.php');

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $goal = $_POST['goal'];

    // Calculate BMI
    $height_in_meters = $height / 100;
    $bmi = $weight / ($height_in_meters * $height_in_meters);

    // Update user information
    $update_sql = "UPDATE users SET name=?, email=?, height=?, weight=?, bmi=?, goal=? WHERE username=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssddsss", $name, $email, $height, $weight, $bmi, $goal, $username);

    if ($stmt->execute()) {
        // Redirect to welcome.php with a success message
        header("Location: welcome.php?status=success");
        exit();
    } else {
        echo "Error updating information.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #333;
            color: white;
            padding: 1em 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 1em 0 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 1em;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        main {
            padding: 2em;
            text-align: center;
        }

        .form-box {
            background-color: #fff;
            padding: 2em;
            margin: 1em auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: left;
        }

        .form-box h2 {
            font-size: 2.5em;
            margin-bottom: 1em;
        }

        .form-box form {
            display: flex;
            flex-direction: column;
        }

        .form-box label {
            font-size: 1.1em;
            margin: 0.5em 0;
        }

        .form-box input[type="text"],
        .form-box input[type="email"],
        .form-box input[type="number"],
        .form-box select {
            padding: 0.5em;
            font-size: 1em;
            margin-bottom: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-box input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 0.75em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
        }

        .form-box input[type="submit"]:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em 0;
            margin-top: 2em;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Update Information</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Dashboard</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="my_schedules.php">My Schedules</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="form-box">
            <h2>Update Your Information</h2>
            <form method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <label for="height">Height (cm):</label>
                <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" required>
                
                <label for="weight">Weight (kg):</label>
                <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" required>
                
                <label for="goal">Fitness Goal:</label>
                <select id="goal" name="goal" required>
                    <option value="lose_weight" <?php echo $user['goal'] == 'lose_weight' ? 'selected' : ''; ?>>Lose Weight</option>
                    <option value="increase_muscle" <?php echo $user['goal'] == 'increase_muscle' ? 'selected' : ''; ?>>Increase Muscle</option>
                    <option value="healthy_diet" <?php echo $user['goal'] == 'healthy_diet' ? 'selected' : ''; ?>>Healthy Diet</option>
                </select>
                
                <input type="submit" value="Update Information">
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gym Website. All rights reserved.</p>
    </footer>
</body>
</html>
