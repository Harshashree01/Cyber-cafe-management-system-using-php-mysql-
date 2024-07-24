<?php
include 'db_config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_session'])) {
    $stmt = $conn->prepare("INSERT INTO sessions (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        echo "Session started!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$current_session = $conn->query("SELECT start_time FROM sessions WHERE user_id = $user_id AND end_time IS NULL");

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
</head>
<body>
    <h1>User Panel</h1>
    <?php if ($current_session->num_rows > 0): ?>
        <p>Current session is ongoing</p>
    <?php else: ?>
        <form method="POST">
            <button type="submit" name="start_session">Start Session</button>
        </form>
    <?php endif; ?>
</body>
</html>
