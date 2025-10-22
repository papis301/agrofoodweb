<?php
session_start();
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h2>Bienvenue 👋</h2>
    <p><strong>Téléphone :</strong> <?= $_SESSION['user_phone'] ?></p>
    <p><strong>Solde :</strong> <?= $_SESSION['solde'] ?></p>
    <p><strong>Statut :</strong> <?= $_SESSION['statut'] ?></p>
    <a href="logout.php">Déconnexion</a>
</body>
</html>
