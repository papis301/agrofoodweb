<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

// Vérification de la session
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

$user_phone = $_SESSION['user_phone'];

// Récupération des produits
$stmt = $conn->prepare("SELECT * FROM products WHERE telephone = :phone ORDER BY id DESC");
$stmt->execute([':phone' => $user_phone]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
    <?php include 'analytics.php'; ?>
<head>
<meta charset="UTF-8">
<title>Gérer mes produits - Agro Food</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="assets/img/favicon.png" rel="icon">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/css/main.css" rel="stylesheet">

<style>
body {
    background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
    font-family: "Open Sans", sans-serif;
    min-height: 100vh;
    padding: 20px;
}
.product-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 1100px;
    margin: 20px auto;
}
h2 {
    color: #2a7a2e;
    text-align: center;
    font-weight: 700;
    margin-bottom: 25px;
}
.btn-agro {
    background-color: #2a7a2e;
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    transition: 0.3s;
}
.btn-agro:hover {
    background-color: #256926;
    transform: scale(1.05);
}
.table-responsive {
    overflow-x: auto;
}
table {
    width: 100%;
    min-width: 600px;
}
th {
    background-color: #2a7a2e;
    color: white;
    text-align: center;
}
td {
    vertical-align: middle !important;
}
td img {
    border-radius: 10px;
    object-fit: cover;
}
@media (max-width: 768px) {
    .product-card {
        padding: 20px;
    }
    table {
        font-size: 14px;
    }
    td img {
        width: 50px;
        height: 50px;
    }
}
</style>
</head>
<body>

<div class="product-card" data-aos="fade-up">
    <div class="text-center mb-4">
        <img src="assets/img/logoagrofoodbon.png" alt="Logo AgroFood" style="height:80px;">
        <h2>Mes produits</h2>
    </div>

    <div class="text-end mb-3">
        <a href="add_product.php" class="btn btn-agro"><i class="bi bi-plus-circle"></i> Ajouter un produit</a>
        <a href="dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Tableau de bord</a>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            Aucun produit ajouté pour le moment. <br>
            <a href="add_product.php" class="btn btn-agro mt-2">Ajouter mon premier produit</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Images</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <?php 
                            if (!empty($p['images'])) {
                                $images = json_decode($p['images'], true);
                                if (is_array($images) && count($images) > 0) {
                                    foreach ($images as $img) {
                                        $path = 'uploads/' . basename($img);
                                        if (file_exists($path)) {
                                            echo "<img src='" . htmlspecialchars($path) . "' width='60' height='60' style='margin:3px;'>";
                                        } else {
                                            echo "<span style='color:red;'>Image introuvable</span>";
                                        }
                                    }
                                } else {
                                    echo "Aucune image";
                                }
                            } else {
                                echo "Aucune image";
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($p['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['price'] ?? '') ?> FCFA</td>
                        <td><?= htmlspecialchars($p['category'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['description'] ?? '') ?></td>
                        <td>
                            <a href="produit.php?id=<?= $p['id'] ?>" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit_product.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_product.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce produit ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
