<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; // ✅ Store the role in session

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Parolă incorectă!";
        }
    } else {
        echo "Utilizator inexistent!";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare - Bible Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Autentifică-te</h3>
                </div>
                <div class="card-body">
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nume utilizator</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Parolă</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-success w-100">Autentifică-te</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Nu ai un cont? <a href="register.php">Înregistrează-te aici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
                            