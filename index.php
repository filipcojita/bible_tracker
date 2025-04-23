<?php
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bible Tracker - Acasă</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid bg-primary text-white p-5">
    <div class="container">
        <h1>Bine ai venit la Bible Tracker</h1>
        <p class="lead">Urmărește-ți citirile biblice zilnice, reflectează și crește împreună cu grupul tău de tineri.</p>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Autentificare</h5>
                    <p class="card-text">Ești deja membru? Autentifică-te pentru a trimite citirile biblice și a-ți urmări progresul.</p>
                    <a href="login.php" class="btn btn-primary">Autentifică-te</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Înregistrează-te</h5>
                    <p class="card-text">Nou aici? Înscrie-te pentru a începe să trimiți reflecțiile biblice și a te alătura grupului.</p>
                    <a href="register.php" class="btn btn-secondary">Înregistrează-te</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Clasament</h5>
                    <p class="card-text">Cum te plasezi în grupul tău? Vezi utilizatorii de top pe baza activitatii lor.</p>
                    <a href="leaderboard.php" class="btn btn-info">Vezi Clasamentul</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="alert alert-warning mt-4" role="alert">
        Nu uita să trimiți citirile biblice zilnice! Ai o fereastră de 3 zile pentru a recupera.
    </div>
</div>

<footer class="bg-dark text-white py-3">
    <div class="container text-center">
        <p>&copy; 2025 Bible Tracker. Toate Drepturile Rezervate.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
