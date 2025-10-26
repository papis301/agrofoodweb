<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

$telephone = $_SESSION['user_phone'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord - Agro Food</title>
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
        min-height: 100vh;
        font-family: "Open Sans", sans-serif;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0px 5px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .dashboard-card h2 {
        color: #2a7a2e;
        font-weight: 700;
        margin-bottom: 25px;
    }

    .dashboard-btn {
        display: block;
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        color: white;
        transition: 0.3s;
    }

    .btn-produit { background-color: #28a745; }
    .btn-produit:hover { background-color: #218838; }

    .btn-ajout { background-color: #17a2b8; }
    .btn-ajout:hover { background-color: #138496; }

    .btn-deconnexion { background-color: #dc3545; }
    .btn-deconnexion:hover { background-color: #c82333; }

    .dashboard-card img {
        height: 80px;
        margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="dashboard-card" data-aos="zoom-in">
    <img src="assets/img/logoagrofoodbon.png" alt="Agro Food">
    <h2>Bonjour, <?= htmlspecialchars($telephone) ?> 👋</h2>

    <a href="manage_products.php" class="dashboard-btn btn-produit">🛒 Gérer mes produits</a>
    <a href="add_product.php" class="dashboard-btn btn-ajout">➕ Ajouter un produit</a>
    <a href="logout.php" class="dashboard-btn btn-deconnexion">🚪 Déconnexion</a>

    <p class="mt-3"><a href="index.php"><i class="bi bi-arrow-left"></i> Retour à l’accueil</a></p>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>
