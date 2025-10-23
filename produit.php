<?php
session_start();
require 'db.php'; // connexion PDO

// Vérifie si l'ID du produit est passé en GET
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$product_id = (int)$_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Produit introuvable !";
        exit;
    }

    // Définit des valeurs par défaut pour éviter les warnings
    $product_name = $product['name'] ?? 'Produit sans nom';
    $product_desc = $product['description'] ?? 'Aucune description disponible.';
    $product_image = $product['image'] ?? 'assets/img/default-product.png';
    $product_price = $product['price'] ?? 0;

} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product_name) ?> - Agro Food</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
        body { background: #f4f6f8; font-family: "Open Sans", sans-serif; padding-top:50px; padding-bottom:50px; }
        .product-card { max-width:800px; margin:0 auto; background:#fff; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.1); overflow:hidden; display:flex; flex-wrap:wrap; }
        .product-card img { width:100%; max-width:400px; object-fit:cover; }
        .product-info { padding:30px; flex:1; }
        .product-info h2 { color:#2a7a2e; margin-bottom:15px; }
        .product-info p { font-size:16px; margin-bottom:20px; color:#555; }
        .product-info .price { font-weight:bold; font-size:20px; margin-bottom:20px; color:#007bff; }
        .btn-back { display:inline-block; padding:10px 20px; background-color:#17a2b8; color:white; border-radius:8px; text-decoration:none; font-weight:600; transition:0.3s; }
        .btn-back:hover { background-color:#138496; }
        @media (max-width:768px) { .product-card { flex-direction:column; } .product-card img { max-width:100%; } }
    </style>
</head>
<body>
    <div class="product-card" data-aos="fade-up">
        <img src="<?= htmlspecialchars($product_image) ?>" alt="<?= htmlspecialchars($product_name) ?>">
        <div class="product-info">
            <h2><?= htmlspecialchars($product_name) ?></h2>
            <p><?= nl2br(htmlspecialchars($product_desc)) ?></p>
            <div class="price"><?= number_format($product_price,0,',',' ') ?> F CFA</div>
            <a href="index.php" class="btn-back"><i class="bi bi-arrow-left"></i> Retour aux produits</a>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>
