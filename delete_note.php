<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$con = new mysqli("localhost", "root", "", "trisha");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $note_id = intval($_GET['id']);

    $stmt = $con->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $note_id, $user_id);

    if ($stmt->execute()) {
        echo "Note deleted successfully! <a href='notes.php'>Back to Notes</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>
