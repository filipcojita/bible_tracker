<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch all users
$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

// Fetch submissions based on selected user (if any)
$submissions_sql = "SELECT s.id, u.username, s.date, s.passage, s.reflection, s.submitted_at 
                    FROM submissions s
                    JOIN users u ON s.user_id = u.id
                    WHERE s.user_id = ?";
$submissions_result = null;
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $stmt = $conn->prepare($submissions_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $submissions_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">📊 Panou de administrare</h2>

    <!-- Users List with Dropdown for Submissions -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">👥 Selectează utilizatorul pentru a vizualiza submisiunile</h5>
        </div>
        <div class="card-body">
            <form method="get" class="form-inline">
                <div class="mb-3">
                    <label for="user_id" class="form-label">Alege un utilizator:</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">Selectează un utilizator</option>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= isset($user_id) && $user_id == $row['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['username']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Vizualizează Submisiuni</button>
            </form>
        </div>
    </div>

    <!-- Display User Submissions if a user is selected -->
    <?php if (isset($submissions_result)): ?>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">📖 Submisiuni ale utilizatorului selectat</h5>
            </div>
            <div class="card-body">
                <?php if ($submissions_result->num_rows > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Utilizator</th>
                                <th>Data</th>
                                <th>Pasaj Biblic</th>
                                <th>Reflecție</th>
                                <th>Data trimiterii</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $submissions_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= $row['date'] ?></td>
                                    <td><?= htmlspecialchars($row['passage']) ?></td>
                                    <td><?= htmlspecialchars($row['reflection']) ?></td>
                                    <td><?= $row['submitted_at'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Nu există submisiuni pentru acest utilizator.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="mt-4 text-center">
        <a href="dashboard.php" class="btn btn-secondary">⬅ Înapoi la Dashboard</a>
        <a href="logout.php" class="btn btn-danger">🔴 Deconectează-te</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
