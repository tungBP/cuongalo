<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Include your database connection file here
include('config.php');

$sql = "SELECT * FROM classes";
$result = $conn->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Website - Classes</title>
    <link rel="stylesheet" href="classes.css">
</head>
<body>
    <header>
        <h1>Our Classes</h1>
        <nav>
            <ul>
                <li><a href="welcome.php">Dashboard</a></li>
                <li><a href="my_information.php">My Information</a></li>
                <li><a href="my_schedules.php">My Schedules</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="classes">
            <h2>Available Classes</h2>
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
                    <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($class['name']); ?></td>
                        <td><?php echo htmlspecialchars($class['level']); ?></td>
                        <td><?php echo htmlspecialchars($class['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($class['description']); ?></td>
                        <td><?php echo htmlspecialchars($class['goal']); ?></td>
                        <td><?php echo htmlspecialchars($class['schedule']); ?></td>
                        <td>
                            <form action="join_class.php" method="post">
                                <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                                <button type="submit" class="btn">Join</button>
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
