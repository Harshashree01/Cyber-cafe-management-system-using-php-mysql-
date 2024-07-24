<?php
include 'db_config.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['end_session'])) {
    $session_id = $_POST['session_id'];
    $end_time = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("UPDATE sessions SET end_time = ?, total_time = TIMESTAMPDIFF(SECOND, start_time, ?) WHERE id = ?");
    $stmt->bind_param("ssi", $end_time, $end_time, $session_id);
    
    if ($stmt->execute()) {
        echo "Session ended!";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$sessions = $conn->query("SELECT sessions.id, users.username, sessions.start_time, sessions.end_time, sessions.total_time FROM sessions JOIN users ON sessions.user_id = users.id WHERE sessions.end_time IS NULL");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Total Time (Seconds)</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $sessions->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>
            <td><?php echo $row['total_time']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="session_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="end_session">End Session</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
