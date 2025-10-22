<?php
session_start();
if (!isset($_SESSION['user_phone'])) {
    header("Location: login.php");
    exit;
}

// 🔹 Connexion à la base de données MySQL
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "agroweb2";
$conn = new mysqli($host, $user, $pass, $dbname);

// Vérification connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// 🔹 Récupération des produits du téléphone connecté
$telephone = $_SESSION['user_phone'];
$sql = "SELECT * FROM products WHERE telephone = '$telephone' ORDER BY created_at DESC";
$result = $conn->query($sql);
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


    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> FCFA</td>
                    <td>
                        <?php 
                        if (!empty($row['images'])) {
                            $images = json_decode($row['images'], true);
                            if (is_array($images)) {
                                foreach ($images as $img) {
                                    echo "<img src='".htmlspecialchars($img)."' width='60' height='60' style='border-radius:5px; margin:2px;'>";
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
                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="edit">✏️ Modifier</a>
                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Supprimer ce produit ?');">🗑 Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="empty">Aucun produit trouvé.</p>
    <?php endif; ?>
</main>

</body>
</html>

<?php $conn->close(); ?>
