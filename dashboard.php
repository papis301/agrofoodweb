<?php
session_start();
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

$telephone = $_SESSION['user_phone'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            width: 90%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn-produit {
            background-color: #28a745;
        }

        .btn-deconnexion {
            background-color: #dc3545;
        }

        .btn-profile {
            background-color: #17a2b8;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>

<header>
    <h1>👋 Bienvenue sur ton tableau de bord</h1>
</header>

<main>
    <h2>Bonjour, <?= htmlspecialchars($telephone) ?></h2>

    <!-- ✅ Nouveau bouton : Gérer les produits -->
    <a href="manage_products.php" class="btn btn-produit">🛒 Gérer mes produits</a>

    <!-- Exemple d'autres boutons -->
    <a href="add_product.php" class="btn btn-profile">➕ Ajouter un produit</a>
    <a href="logout.php" class="btn btn-deconnexion">🚪 Déconnexion</a>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> AgroFood - Tous droits réservés</p>
</footer>

</body>
</html>
