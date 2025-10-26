<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$product_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produit introuvable !";
    exit;
}

$product_name = $product['name'] ?? 'Produit sans nom';
$product_desc = $product['description'] ?? 'Aucune description disponible.';
$product_price = $product['price'] ?? 0;
$product_images = $product['images'] ?? '';
$owner_phone = $product['telephone'] ?? 'Numéro non disponible';
$images = json_decode($product_images, true);
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
        body {
            background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
            font-family: "Open Sans", sans-serif;
            padding: 50px 0;
            color: #333;
        }
        .product-card {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        .product-images, .product-info {
            flex: 1 1 300px;
            padding: 20px;
        }
        .product-images .carousel-img {
            width: 400px;
            height: 400px;
            object-fit: cover;
            margin: 0 auto;
            border-radius: 10px;
        }
        .product-thumbnails {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .product-thumbnails img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin: 0 5px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }
        .product-thumbnails img:hover {
            border-color: #2a7a2e;
        }
        .product-info h2 {
            color: #2a7a2e;
            margin-bottom: 15px;
        }
        .product-info p {
            font-size: 16px;
            margin-bottom: 15px;
            color: #555;
        }
        .product-info .price {
            font-weight: bold;
            font-size: 20px;
            margin-bottom: 15px;
            color: #007bff;
        }
        .product-info .contact {
            font-weight: 600;
            margin-bottom: 25px;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #17a2b8;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-back:hover { background-color: #138496; }

        @media (max-width:768px) {
            .product-card { flex-direction: column; }
            .product-images .carousel-img { width: 100%; height: 300px; }
        }
    </style>
</head>
<body>
<div class="product-card" data-aos="fade-up">

    <div class="product-images">
        <?php if (!empty($images) && is_array($images) && count($images) > 0): ?>
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($images as $index => $img): 
                    $img = trim($img);
                    if (!str_starts_with($img, 'uploads/')) {
                        $img = 'uploads/' . basename($img);
                    }
                    if (!file_exists($img)) {
                        $img = 'assets/img/default-product.png';
                    }
                ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= htmlspecialchars($img) ?>" class="d-block carousel-img" alt="Produit">
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>
        <!-- Miniatures -->
        <div class="product-thumbnails">
            <?php foreach ($images as $thumbIndex => $thumbImg):
                $thumbImg = trim($thumbImg);
                if (!str_starts_with($thumbImg, 'uploads/')) {
                    $thumbImg = 'uploads/' . basename($thumbImg);
                }
                if (!file_exists($thumbImg)) {
                    $thumbImg = 'assets/img/default-product.png';
                }
            ?>
            <img src="<?= htmlspecialchars($thumbImg) ?>" alt="Miniature" data-bs-target="#productCarousel" data-bs-slide-to="<?= $thumbIndex ?>">
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <img src="assets/img/default-product.png" class="carousel-img" alt="Aucune image">
        <?php endif; ?>
    </div>

    <div class="product-info">
        <h2><?= htmlspecialchars($product_name) ?></h2>
        <p><?= nl2br(htmlspecialchars($product_desc)) ?></p>
        <div class="price"><?= number_format($product_price,0,',',' ') ?> F CFA</div>
        <p class="contact"><strong>Contact :</strong> <?= htmlspecialchars($owner_phone) ?></p>
        <a href="index.php" class="btn-back"><i class="bi bi-arrow-left"></i> Retour aux produits</a>
    </div>

</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>
AOS.init();
</script>
</body>
</html>
