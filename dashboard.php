<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if user is not logged in
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username']; // Get username for greeting
$today = date("Y-m-d");

// Get the last 3 days (today, yesterday, and the day before yesterday)
$last_three_days = [];
for ($i = 0; $i < 3; $i++) {
    $last_three_days[] = date("Y-m-d", strtotime("$today -$i day"));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $date = $_POST['date'];
    $passage = $_POST['passage'];
    $reflection = $_POST['reflection'];

    // Check if the selected date is within the last 3 days
    if (!in_array($date, $last_three_days)) {
        echo "<script>alert('❌ You can only submit responses for the last 3 days.');</script>";
    } else {
        // Check if the user already submitted for this day
        $sql = "SELECT COUNT(*) AS count FROM submissions WHERE user_id = ? AND date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            echo "<script>alert('❌ You have already submitted for this day.');</script>";
        } else {
            // Insert the form data into the database
            $sql = "INSERT INTO submissions (user_id, date, passage, reflection) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $user_id, $date, $passage, $reflection);

            if ($stmt->execute()) {
                echo "<script>alert('Submission successful!');</script>";
            } else {
                echo "<script>alert('Error submitting your entry. Please try again.');</script>";
            }
        }
    }
}

// Fetch submitted dates for this user
$sql = "SELECT date FROM submissions WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$submitted_dates = [];

while ($row = $result->fetch_assoc()) {
    $submitted_dates[] = $row['date'];
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bible Tracker - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            margin-bottom: 20px;
        }
        .welcome-message {
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
        }
        .submitted-dates ul {
            list-style-type: none;
            padding-left: 0;
        }
        .submitted-dates li {
            background-color: #f8f9fa;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Bible Tracker</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="navbar-text welcome-message">
                        Bine ai venit, <?= htmlspecialchars($username) ?>!
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Deconectează-te</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Submit Your Bible Reading</h2>

    <!-- Submission Form -->
    <form method="post" class="form-group">
        <div class="mb-3">
            <label for="date" class="form-label">Select Date:</label>
            <input type="date" name="date" id="date-picker" max="<?= $today ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="passage" class="form-label">Bible Passage:</label>
            <input type="text" name="passage" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="reflection" class="form-label">Reflection:</label>
            <textarea name="reflection" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <!-- User Past Submissions -->
    <h3 class="mt-4">Your Past Submissions:</h3>
    <div class="submitted-dates">
        <ul>
            <?php foreach ($submitted_dates as $date): ?>
                <li><strong><?= htmlspecialchars($date) ?></strong></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Leaderboard and Logout -->
    <a href="leaderboard.php" class="btn btn-info">View Leaderboard</a>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php" class="btn btn-warning">Admin Panel</a>
<?php endif; ?>

<a href="logout.php" class="btn btn-danger">Deconectează-te</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let submittedDates = <?= json_encode($submitted_dates) ?>;
    let datePicker = document.getElementById("date-picker");

    datePicker.addEventListener("change", function() {
        if (submittedDates.includes(this.value)) {
            alert("❌ You already submitted for this day.");
            this.value = ""; // Clear the selected date
        }
    });
</script>

</body>
</html>
