<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Include your database connection file here
include('config.php');

$username = $_SESSION['username'];
$sql = "SELECT c.id, c.name, c.level, c.instructor, c.description, c.goal, c.schedule
        FROM enrollments e
        JOIN classes c ON e.class_id = c.id
        WHERE e.username = '$username'";
$result = $conn->query($sql);

$schedules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schedule</title>
    <link rel="stylesheet" href="my_schedules.css">
</head>
<body>
    <header>
        <h1>My Schedule</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Dashboard</a></li>
                <li><a href="my_information.php">My Information</a></li>
                <li><a href="classes.php">Classes</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="schedules">
            <h2>My Enrolled Classes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Level</th>
                        <th>Instructor</th>
                        <th>Description</th>
                        <th>Goal</th>
                        <th>Schedule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($schedule['name']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['level']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['description']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['goal']); ?></td>
                        <td><?php echo htmlspecialchars($schedule['schedule']); ?></td>
                        <td>
                            <form action="cancel_class.php" method="post">
                                <input type="hidden" name="class_id" value="<?php echo $schedule['id']; ?>">
                                <button type="submit" class="btn">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gym Website. All rights reserved.</p>
    </footer>
</body>
</html>
