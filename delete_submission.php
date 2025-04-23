<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM submissions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "✅ Submission deleted!";
    } else {
        echo "❌ Error deleting submission.";
    }
}

header("Location: admin.php");
exit();
?>
