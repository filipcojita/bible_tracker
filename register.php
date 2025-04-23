<?php
include 'db.php';
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($result) == 0) {
            // Insert the new user into the database
            $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $insert_query)) {
                header('Location: login.php'); // Redirect to login page after successful registration
                exit();
            } else {
                $error = "Eroare la crearea contului. Încercați din nou.";
            }
        } else {
            $error = "Numele de utilizator există deja. Alegeți altul.";
        }
    } else {
        $error = "Parolele nu se potrivesc. Încercați din nou.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare - Bible Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Înregistrează-te</h3>
                </div>
                <div class="card-body">
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nume utilizator</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Parolă</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmați parola</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error; ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-success w-100">Înregistrează-te</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p>Ai deja un cont? <a href="login.php">Autentifică-te aici</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
