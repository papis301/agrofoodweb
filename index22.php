<?php
require 'db.php';

// Récupérer tous les produits
$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>AgriCulture - Produits</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- ✅ HEADER -->
<?php include 'header.php'; ?>

<!-- ✅ SECTION PRODUITS -->
<section class="products py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>🌾 Tous les produits</h2>
            <p>Découvrez les produits disponibles sur notre plateforme</p>
        </div>

        <div class="row">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $p): ?>
                    <?php
                    $image = "assets/img/no-image.png";
                    if (!empty($p['images'])) {
                        $imgs = json_decode($p['images'], true);
                        if (is_array($imgs) && count($imgs) > 0) {
                            $image = htmlspecialchars($imgs[0]);
                        }
                    }
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card shadow-sm">
                            <img src="<?= $image ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="height:200px;object-fit:cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="card-text text-success fw-bold"><?= htmlspecialchars($p['price']) ?> FCFA</p>
                                <p class="text-muted small">📞 <?= htmlspecialchars($p['telephone']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Aucun produit disponible pour le moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ✅ FOOTER -->
<?php include 'footer.php'; ?>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
