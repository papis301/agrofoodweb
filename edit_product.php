<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// 🔹 Vérifie si un ID de produit est passé
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_products.php");
    exit;
}

$product_id = (int) $_GET['id'];
$telephone = $_SESSION['user_phone'];

// 🔹 Récupération du produit
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id AND telephone = :telephone");
$stmt->execute([':id' => $product_id, ':telephone' => $telephone]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("❌ Produit introuvable ou non autorisé.");
}

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $uploadedImages = json_decode($product['images'], true) ?? [];

    // 📸 Upload de nouvelles images (si ajoutées)
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetFile = $targetDir . time() . "_" . $key . "_" . $fileName;
            if (move_uploaded_file($tmpName, $targetFile)) {
                $uploadedImages[] = $targetFile;
            }
        }
    }

    $imagesJSON = json_encode($uploadedImages);

    // 🔹 Mise à jour du produit
    $update = $conn->prepare("UPDATE products SET name = :name, price = :price, images = :images WHERE id = :id AND telephone = :telephone");
    $success = $update->execute([
        ':name' => $name,
        ':price' => $price,
        ':images' => $imagesJSON,
        ':id' => $product_id,
        ':telephone' => $telephone
    ]);

    if ($success) {
        header("Location: manage_products.php?success=1");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Erreur lors de la mise à jour.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le produit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 400px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { background: #218838; }
        img {
            margin: 5px;
            border-radius: 5px;
        }
        h2 { text-align: center; color: #28a745; }
        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Modifier le produit</h2>

    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    <input type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
    
    <label>Ajouter de nouvelles images :</label>
    <input type="file" name="images[]" accept="image/*" multiple>

    <p>📸 Images actuelles :</p>
    <?php
    if (!empty($product['images'])) {
        $images = json_decode($product['images'], true);
        if (is_array($images)) {
            foreach ($images as $img) {
                echo "<img src='" . htmlspecialchars($img) . "' width='60' height='60'>";
            }
        }
    } else {
        echo "<p>Aucune image.</p>";
    }
    ?>

    <button type="submit">💾 Enregistrer</button>
    <a href="manage_products.php" class="back">⬅ Retour</a>
</form>

</body>
</html>
