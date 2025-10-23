<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (empty($phone) || empty($password)) {
        $error = "❌ Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM usersagrofood WHERE phone = :phone LIMIT 1");
        $stmt->execute([':phone' => $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "⚠️ Numéro ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - Agro Food</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Bootstrap -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
        background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: "Open Sans", sans-serif;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 380px;
        text-align: center;
    }
    .login-card h2 {
        color: #2a7a2e;
        font-weight: 700;
        margin-bottom: 25px;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 10px 12px;
    }
    .btn-login {
        background-color: #2a7a2e;
        border: none;
        color: white;
        padding: 10px;
        width: 100%;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-login:hover {
        background-color: #256926;
    }
    .error {
        color: #d9534f;
        margin-bottom: 15px;
        font-size: 14px;
    }
    .login-card a {
        color: #2a7a2e;
        text-decoration: none;
    }
    .login-card a:hover {
        text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-card" data-aos="zoom-in">
    <img src="assets/img/logoagrofoodbon.png" alt="Agro Food" style="height:80px; margin-bottom:10px;">
    <h2>Connexion</h2>

    <?php if (!empty($error)): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Numéro de téléphone" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="btn-login">Se connecter</button>

        <p class="mt-3">
            Pas encore de compte ? <a href="register.php">Créer un compte</a>
        </p>
        <p><a href="index.php"><i class="bi bi-arrow-left"></i> Retour à l’accueil</a></p>
    </form>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
