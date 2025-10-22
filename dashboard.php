<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - AgroFood</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
        }

        main {
            text-align: center;
            margin-top: 40px;
        }

        .info {
            background: white;
            display: inline-block;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
        }

        .info p {
            font-size: 18px;
            margin: 8px 0;
        }

        a.btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            margin: 10px;
        }

        a.btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .logout {
            background-color: #dc3545;
        }

        .logout:hover {
            background-color: #b52a37;
        }
    </style>
</head>
<body>

<header>
    <h1>Tableau de bord AgroFood</h1>
</header>

<main>
    <div class="info">
        <h2>Bienvenue 👋</h2>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($_SESSION['user_phone']) ?></p>
        <p><strong>Solde :</strong> <?= htmlspecialchars($_SESSION['solde']) ?> FCFA</p>
        <p><strong>Statut :</strong> <?= htmlspecialchars($_SESSION['statut']) ?></p>

        <!-- Bouton vers la gestion des produits -->
        <a href="manage_products.php" class="btn">🛒 Gérer mes produits</a>

        <!-- Bouton de déconnexion -->
        <a href="logout.php" class="btn logout">🚪 Déconnexion</a>
    </div>
</main>

</body>
</html>
