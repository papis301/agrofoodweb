<?php
session_start();

// ✅ Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_phone']) || !isset($_SESSION['firebase_id'])) {
    header("Location: login.php");
    exit;
}

// --- Connexion à la base de données ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "agroweb2";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// --- Récupération des infos utilisateur depuis la session ---
$telephone = $_SESSION['user_phone'];
$firebase_id = $_SESSION['firebase_id'];

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $imagePath = "";

    // Upload image
    if (!empty($_FILES['images']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($_FILES['images']['name']);
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES['images']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    // Insertion dans la base
    $stmt = $conn->prepare("INSERT INTO products (name, price, firebase_id, telephone, images) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $price, $firebase_id, $telephone, $imagePath);

    if ($stmt->execute()) {
        // ✅ Redirection automatique vers la page de gestion
        header("Location: manage_products.php?success=1");
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
    <title>Ajouter un produit</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0 10px #ccc; width: 350px; }
        input, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        h2 { text-align: center; color: #007bff; }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Ajouter un produit</h2>

    <input type="text" name="name" placeholder="Nom du produit" required>
    <input type="text" name="price" placeholder="Prix" required>
    <input type="file" name="images" accept="image/*" required>

    <button type="submit">Ajouter</button>
</form>

</body>
</html>
