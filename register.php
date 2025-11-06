<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; // connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($phone) || empty($password)) {
        $error = "❌ Tous les champs sont obligatoires.";
    } else {
        try {
            // Vérifie si le numéro existe déjà
            $stmt = $conn->prepare("SELECT id FROM usersagrofood WHERE phone = ?");
            $stmt->execute([$phone]);

            if ($stmt->rowCount() > 0) {
                $error = "⚠️ Ce numéro existe déjà dans la base de données.";
            } else {
                // Hachage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertion dans la table
                $insert = $conn->prepare("INSERT INTO usersagrofood (phone, password, solde, statut, type_inscription)
                                          VALUES (?, ?, 0, 'activé', 'ordinateur')");
                $insert->execute([$phone, $hashedPassword]);

                $success = "✅ Inscription réussie ! <a href='login.php'>Se connecter</a>";
            }

        } catch (PDOException $e) {
            $error = "🔥 Erreur MySQL : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <?php include 'analytics.php'; ?>
<head>
  <meta charset="UTF-8">
  <title>Inscription - Agro Food</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Bootstrap & AOS -->
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
        min-height: 100vh;
        font-family: "Open Sans", sans-serif;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 380px;
        text-align: center;
    }

    .register-card img {
        height: 80px;
        margin-bottom: 15px;
    }

    .register-card h2 {
        color: #2a7a2e;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 10px 12px;
        margin-bottom: 15px;
    }

    .btn-register {
        background-color: #2a7a2e;
        border: none;
        color: white;
        padding: 12px;
        width: 100%;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-register:hover {
        background-color: #256926;
    }

    .message {
        margin-bottom: 15px;
        font-size: 14px;
        color: #d9534f;
    }

    .success {
        color: #28a745;
    }

    .register-card a {
        color: #2a7a2e;
        text-decoration: none;
    }
    .register-card a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="register-card" data-aos="zoom-in">
    <img src="assets/img/logoagrofoodbon.png" alt="Agro Food">
    <h2>Créer un compte</h2>

    <?php if (!empty($error)): ?>
        <p class="message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p class="message success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="phone" class="form-control" placeholder="Numéro de téléphone" required>
        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
        <button type="submit" class="btn-register">S’inscrire</button>
    </form>

    <p class="mt-3">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    <p><a href="index.php"><i class="bi bi-arrow-left"></i> Retour à l’accueil</a></p>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
