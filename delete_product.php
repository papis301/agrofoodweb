<?php
session_start();

// Vérifie la session
if (!isset($_SESSION['user_phone']) || !isset($_SESSION['firebase_id'])) {
    header("Location: login.php");
    exit;
}

// Vérifie si l'ID est présent
if (!isset($_GET['id'])) {
    header("Location: list_products.php");
    exit;
}

$product_id = intval($_GET['id']);

// --- Connexion MySQL ---
require 'db.php';

// --- Récupère le produit pour supprimer ses images ---
$result = $conn->query("SELECT images FROM products WHERE id = $product_id");
if ($result->num_rows === 0) {
    header("Location: list_products.php");
    exit;
}

$product = $result->fetch_assoc();
$images = json_decode($product['images'] ?? '[]', true) ?: [];

// Supprime les fichiers images
foreach ($images as $img) {
    if (file_exists($img)) {
        unlink($img);
    }
}

// Supprime le produit de la base
$conn->query("DELETE FROM products WHERE id = $product_id");

// Redirige vers la liste
header("Location: manage_products.php?deleted=1");
exit;
?>
