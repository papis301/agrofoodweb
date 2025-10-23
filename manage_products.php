<?php
session_start();

// ✅ Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

// 🔹 Connexion à la base de données PDO
require 'db.php';

// 🔹 Récupération des produits du téléphone connecté
$telephone = $_SESSION['user_phone'];

try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE telephone = :telephone ORDER BY created_at DESC");
    $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des produits : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer mes produits</title>
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
            padding: 15px;
            text-align: center;
        }

        main {
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
            text-align: center;
        }

        .actions a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            color: white;
        }

        .edit {
            background: #28a745;
        }

        .delete {
            background: #dc3545;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin: 10px 5px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .empty {
            text-align: center;
            color: #777;
            margin-top: 30px;
        }

        img {
            border-radius: 5px;
            margin: 2px;
        }
    </style>
</head>
<body>

<header>
    <h1>🛒 Gérer mes produits</h1>
</header>

<main>
    <a href="add_product.php" class="btn">➕ Ajouter un produit</a>
    <a href="dashboard.php" class="btn">🏠 Retour au tableau de bord</a>

    <?php if (isset($_GET['success'])): ?>
        <p style="color:green; text-align:center;">✅ Produit ajouté avec succès !</p>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Images</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> FCFA</td>
                    <td>
                        <?php 
                        if (!empty($row['images'])) {
                            $images = json_decode($row['images'], true);
                            if (is_array($images) && count($images) > 0) {
                                foreach ($images as $img) {
                                    echo "<img src='" . htmlspecialchars($img) . "' width='60' height='60'>";
                                }
                            } else {
                                echo "Aucune";
                            }
                        } else {
                            echo "Aucune";
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?= urlencode($row['id']) ?>" class="edit">✏️ Modifier</a>
                        <a href="delete_product.php?id=<?= urlencode($row['id']) ?>" class="delete" onclick="return confirm('Supprimer ce produit ?');">🗑 Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="empty">Aucun produit trouvé.</p>
    <?php endif; ?>
</main>

</body>
</html>
