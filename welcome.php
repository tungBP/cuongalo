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

// Fetch upcoming classes
$upcoming_classes_sql = "SELECT c.name, c.schedule FROM enrollments e JOIN classes c ON e.class_id = c.id WHERE e.username = '$username'";
$upcoming_classes_result = $conn->query($upcoming_classes_sql);

$upcoming_classes = [];
if ($upcoming_classes_result->num_rows > 0) {
    while ($row = $upcoming_classes_result->fetch_assoc()) {
        $upcoming_classes[] = $row;
    }
}

// Generate recommendations based on BMI and goal
$recommendations = "";
$bmi = $user['bmi'];

if ($bmi < 18.5) {
    $recommendations = "Your BMI indicates that you are underweight. Consider incorporating more nutrient-dense foods into your diet, such as nuts, seeds, avocados, and lean proteins. Strength training exercises can help you build muscle mass.";
} elseif ($bmi >= 18.5 && $bmi < 24.9) {
    $recommendations = "Your BMI is in the normal range. Maintain a balanced diet and regular exercise routine to stay healthy. Include a mix of cardio, strength training, and flexibility exercises.";
} elseif ($bmi >= 25 && $bmi < 29.9) {
    $recommendations = "Your BMI indicates that you are overweight. Consider adopting a healthier diet with more fruits, vegetables, and whole grains. Incorporate regular cardio exercises, such as walking, running, or cycling, along with strength training.";
} else {
    $recommendations = "Your BMI indicates that you are obese. It's important to consult with a healthcare provider to create a safe and effective weight loss plan. Focus on a balanced diet, portion control, and regular physical activity.";
}

if ($user['goal'] == "lose_weight") {
    $recommendations .= " Since your goal is to lose weight, focus on creating a calorie deficit through a combination of diet and exercise. Consider joining our weight loss classes and using our personal training services.";
} elseif ($user['goal'] == "increase_muscle") {
    $recommendations .= " Since your goal is to increase muscle mass, focus on strength training exercises and ensure you are consuming enough protein. Consider joining our strength training classes and working with a personal trainer.";
} elseif ($user['goal'] == "healthy_diet") {
    $recommendations .= " Since your goal is to maintain a healthy diet, focus on incorporating a variety of nutrient-dense foods into your meals. Consider consulting with our nutritionist for personalized dietary advice.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, <?php echo htmlspecialchars($user['name']); ?></title>
    <style>
        /* General Styles */
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

        /* Dashboard Section Styles */
        #dashboard {
            padding: 2em;
            text-align: center;
        }

        #dashboard h2 {
            font-size: 2.5em;
            margin-bottom: 1em;
        }

        .info-box {
            background-color: #fff;
            padding: 2em;
            margin: 1em auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: left;
        }

        .info-box h3 {
            font-size: 1.5em;
            margin-bottom: 0.5em;
        }

        .info-box p, .info-box ul {
            font-size: 1.1em;
            margin: 0.5em 0;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
        }

        .info-box ul li {
            background: url('https://source.unsplash.com/20x20/?check,icon') no-repeat left center;
            padding-left: 30px;
            margin-bottom: 0.5em;
        }

        .info-box img {
            width: 100%;
            height: auto;
            border-radius: 4px;
            margin-top: 1em;
        }

        .success-message {
            background-color: #e0ffe0;
            color: #007000;
            border: 1px solid #007000;
            padding: 1em;
            border-radius: 4px;
            margin-bottom: 1em;
            text-align: center;
        }

        /* Footer Styles */
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
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <nav>
            <ul>
                <li><a href="my_information.php">Update Information</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="my_schedules.php">My Schedules</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="dashboard">
            <h2>Your Dashboard</h2>
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="success-message">Information updated successfully.</div>
            <?php endif; ?>
            <div class="info-box">
                <h3>Personal Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Height:</strong> <?php echo htmlspecialchars($user['height']); ?> cm</p>
                <p><strong>Weight:</strong> <?php echo htmlspecialchars($user['weight']); ?> kg</p>
                <p><strong>BMI:</strong> <?php echo round($user['bmi'], 2); ?></p>
                <p><strong>Goal:</strong> <?php echo htmlspecialchars($user['goal']); ?></p>
            </div>
            <div class="info-box">
                <h3>Recommendations</h3>
                <p><?php echo htmlspecialchars($recommendations); ?></p>
            </div>
            <div class="info-box">
                <h3>Upcoming Classes</h3>
                <?php if (count($upcoming_classes) > 0): ?>
                    <ul>
                        <?php foreach ($upcoming_classes as $class): ?>
                            <li><?php echo htmlspecialchars($class['name']) . ' - ' . htmlspecialchars($class['schedule']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No upcoming classes. <a href="classes.php">Join a class</a>.</p>
                <?php endif; ?>
            </div>
            <div class="info-box">
                <h3>Achieve Your Goals</h3>
                <p>Stay committed and patient. Your dedication will lead you to success!</p>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gym Website. All rights reserved.</p>
    </footer>
</body>
</html>
