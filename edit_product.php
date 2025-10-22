<?php
session_start();

// Vérifie la session
if (!isset($_SESSION['user_phone']) || !isset($_SESSION['firebase_id'])) {
    header("Location: login.php");
    exit;
}

// --- Configuration de la base de données ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "agroweb2";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// --- Récupération du produit à modifier ---
if (!isset($_GET['id'])) {
    header("Location: list_products.php");
    exit;
}

$product_id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
if ($result->num_rows === 0) {
    echo "❌ Produit introuvable.";
    exit;
}

$product = $result->fetch_assoc();
$existing_images = json_decode($product['images'] ?? '[]', true) ?: [];

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $telephone = $_SESSION['user_phone'];
    $firebase_id = $_SESSION['firebase_id'];
    $new_images = $existing_images;

    // Suppression d’anciennes images si cochées
    if (!empty($_POST['delete_images'])) {
        foreach ($_POST['delete_images'] as $img) {
            $imgPath = basename($img);
            $filePath = "uploads/" . $imgPath;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $new_images = array_filter($new_images, fn($i) => $i !== $img);
        }
    }

    // Upload de nouvelles images
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        foreach ($_FILES['images']['name'] as $key => $fileName) {
            $targetFile = $targetDir . time() . "_" . basename($fileName);
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFile)) {
                $new_images[] = $targetFile;
            }
        }
    }

    // Met à jour le produit
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, telephone=?, firebase_id=?, images=? WHERE id=?");
    $images_json = json_encode(array_values($new_images));
    $stmt->bind_param("sssssi", $name, $price, $telephone, $firebase_id, $images_json, $product_id);

    if ($stmt->execute()) {
        header("Location: manage_products.php?updated=1");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Erreur : " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un produit</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; display: flex; justify-content: center; align-items: flex-start; padding-top: 40px; }
        form { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px #ccc; width: 400px; }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        h2 { text-align: center; color: #007bff; }
        .images { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
        .images div { position: relative; }
        .images img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ccc; }
        .images input[type=checkbox] { position: absolute; top: 5px; right: 5px; }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Modifier le produit</h2>

    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
    <input type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>

    <h3>Images actuelles :</h3>
    <div class="images">
        <?php foreach ($existing_images as $img): ?>
            <div>
                <img src="<?= htmlspecialchars($img) ?>" alt="Image produit">
                <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($img) ?>"> ❌
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Ajouter de nouvelles images :</h3>
    <input type="file" name="images[]" multiple accept="image/*">

    <button type="submit">💾 Enregistrer</button>
</form>

</body>
</html>
