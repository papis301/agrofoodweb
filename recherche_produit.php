<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $produit = trim($_POST['produit_recherche']);
    $desc = trim($_POST['description']);
    $user = $_SESSION['user_phone'];
    $uploadedImages = [];

    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = $uploadDir . time() . "_" . $fileName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedImages[] = $targetPath;
            }
        }
    }

    if ($produit !== "") {
        $stmt = $conn->prepare("INSERT INTO demandes (user_phone, produit_recherche, description, images) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user, $produit, $desc, json_encode($uploadedImages)]);
        $message = "✅ Votre demande a été publiée avec succès.";
    } else {
        $message = "❌ Veuillez entrer le nom du produit recherché.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rechercher un produit - Agro Food</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    body {
      background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
      min-height: 100vh;
      padding-top: 100px;
    }
    .form-container {
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 15px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
  <div class="form-container">
    <h2 class="mb-4 text-center text-success">Publier une recherche de produit</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="produit_recherche" class="form-label">Nom du produit recherché</label>
        <input type="text" id="produit_recherche" name="produit_recherche" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description (facultatif)</label>
        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
      </div>

      <div class="mb-3">
        <label for="images" class="form-label">Ajouter des images (facultatif)</label>
        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
        <small class="text-muted">Vous pouvez sélectionner plusieurs images</small>
      </div>

      <button type="submit" class="btn btn-success w-100">Publier ma demande</button>
    </form>
  </div>
</div>

</body>
</html>
