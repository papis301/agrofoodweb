<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// ✅ Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';
$telephone = $_SESSION['user_phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $uploadedImages = [];

    if (empty($name) || empty($price)) {
        $error = "❌ Tous les champs sont obligatoires.";
    } else {
        if (!empty($_FILES['images']['name'][0])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetFile = $targetDir . time() . "_" . $key . "_" . preg_replace('/\s+/', '_', $fileName);
                $fileType = mime_content_type($tmpName);

                if (strpos($fileType, 'image') === false) continue;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $uploadedImages[] = $targetFile;
                }
            }
        }

        $imagesJSON = json_encode($uploadedImages);

        try {
            $stmt = $conn->prepare("INSERT INTO products (name, price, telephone, images, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $price, $telephone, $imagesJSON]);
            header("Location: manage_products.php?success=1");
            exit;
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
<title>Ajouter un produit - Agro Food</title>
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
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}
.form-card {
    background: rgba(255,255,255,0.95);
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 400px;
    text-align: center;
}
.form-card h2 {
    color: #2a7a2e;
    margin-bottom: 25px;
    font-weight: 700;
}
.form-control {
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 10px 12px;
    margin-bottom: 15px;
}
.btn-agro {
    background-color: #2a7a2e;
    border: none;
    color: white;
    padding: 10px;
    width: 100%;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}
.btn-agro:hover {
    background-color: #256926;
}
.error {
    color: #d9534f;
    margin-bottom: 15px;
    font-size: 14px;
}
.back {
    display: block;
    margin-top: 15px;
    color: #2a7a2e;
    text-decoration: none;
}
.back:hover {
    text-decoration: underline;
}
</style>
</head>
<body>

<div class="form-card" data-aos="zoom-in">
    <h2>Ajouter un produit</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" class="form-control" placeholder="Nom du produit" required>
        <input type="text" name="price" class="form-control" placeholder="Prix" required>
        <input type="file" name="images[]" class="form-control" accept="image/*" multiple required>
        <button type="submit" class="btn-agro">Ajouter</button>
    </form>

    <a href="manage_products.php" class="back"><i class="bi bi-arrow-left"></i> Retour à la liste des produits</a>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
