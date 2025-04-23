<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if user is not logged in
    exit();
}
?>

<?php
include 'db.php';

// Query to fetch top 10 users based on number of submissions
$sql = "
    SELECT users.username, COUNT(submissions.date) AS total_submissions
    FROM submissions
    JOIN users ON submissions.user_id = users.id
    GROUP BY users.id
    ORDER BY total_submissions DESC
    LIMIT 10
";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Bible Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Leaderboard - Top 10 Users</h2>
    
    <!-- Display Leaderboard -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Submissions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rank = 1;
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['total_submissions'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-primary">Trimite un raspuns</a>
    <a href="logout.php" class="btn btn-danger">Deconectează-te</a>
    <a href="index.php" class="btn btn-secondary">Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
