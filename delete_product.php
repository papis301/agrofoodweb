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

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_products.php");
    exit;
}

$product_id = (int) $_GET['id'];
$telephone = $_SESSION['user_phone'];

try {
    // 🔹 Récupère les images pour les supprimer du dossier
    $stmt = $conn->prepare("SELECT images FROM products WHERE id = :id AND telephone = :telephone");
    $stmt->execute([':id' => $product_id, ':telephone' => $telephone]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("❌ Produit introuvable ou non autorisé.");
    }

    // 🔹 Supprime les fichiers image (si présents)
    $images = json_decode($product['images'], true);
    if (is_array($images)) {
        foreach ($images as $img) {
            if (file_exists($img)) {
                unlink($img);
            }
        }
    }

    // 🔹 Supprime le produit de la base
    $delete = $conn->prepare("DELETE FROM products WHERE id = :id AND telephone = :telephone");
    $delete->execute([':id' => $product_id, ':telephone' => $telephone]);

    header("Location: manage_products.php?deleted=1");
    exit;

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
