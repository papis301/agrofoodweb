<?php
session_start();

// ✅ Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

// --- Connexion à la base de données ---
require 'db.php';

// --- Récupération des infos utilisateur ---
$user_id = $_SESSION['user_id'];
$telephone = $_SESSION['user_phone'];

// --- Traitement du formulaire ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $uploadedImages = [];

    if (empty($name) || empty($price)) {
        echo "<p style='color:red;'>❌ Tous les champs sont obligatoires.</p>";
    } else {
        // 📸 Upload de plusieurs images
        if (!empty($_FILES['images']['name'][0])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetFile = $targetDir . time() . "_" . $key . "_" . preg_replace('/\s+/', '_', $fileName);

                // Vérifie le type MIME pour éviter les fichiers malveillants
                $fileType = mime_content_type($tmpName);
                if (strpos($fileType, 'image') === false) {
                    echo "<p style='color:red;'>❌ Le fichier $fileName n'est pas une image valide.</p>";
                    continue;
                }

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $uploadedImages[] = $targetFile;
                }
            }
        }

        // On stocke les chemins d'images en JSON
        $imagesJSON = json_encode($uploadedImages);

        try {
            // --- Insertion dans la base ---
            $stmt = $conn->prepare("
                INSERT INTO products (name, price, firebase_id, telephone, images, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$name, $price, $user_id, $telephone, $imagesJSON]);

            // ✅ Redirection automatique vers la page de gestion
            header("Location: manage_products.php?success=1");
            exit;
        } catch (PDOException $e) {
            echo "<p style='color:red;'>🔥 Erreur MySQL : " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit</title>
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
            width: 380px; 
        }
        input, button { 
            width: 100%; 
            padding: 10px; 
            margin-top: 10px; 
            border-radius: 5px; 
            border: 1px solid #ccc; 
        }
        button { 
            background: #007bff; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        button:hover { background: #0056b3; }
        h2 { text-align: center; color: #007bff; }
        .back { 
            display: block; 
            text-align: center; 
            margin-top: 15px; 
            text-decoration: none; 
            color: #007bff; 
        }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Ajouter un produit</h2>

    <input type="text" name="name" placeholder="Nom du produit" required>
    <input type="text" name="price" placeholder="Prix" required>
    
    <!-- ✅ Sélection multiple d'images -->
    <input type="file" name="images[]" accept="image/*" multiple required>

    <button type="submit">Ajouter</button>

    <a href="manage_products.php" class="back">⬅ Retour à la liste des produits</a>
</form>

</body>
</html>
