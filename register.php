<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; // connexion PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($phone) || empty($password)) {
        echo "❌ Tous les champs sont obligatoires.";
        exit;
    }

    try {
        // Vérifie si le numéro existe déjà
        $stmt = $conn->prepare("SELECT id FROM usersagrofood WHERE phone = ?");
        $stmt->execute([$phone]);

        if ($stmt->rowCount() > 0) {
            echo "⚠️ Ce numéro existe déjà dans la base de données.";
            exit;
        }

        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la table
        $insert = $conn->prepare("INSERT INTO usersagrofood (phone, password, solde, statut, type_inscription)
                                  VALUES (?, ?, 0, 'activé', 'ordinateur')");
        $insert->execute([$phone, $hashedPassword]);

        echo "✅ Inscription réussie ! <a href='login.php'>Se connecter</a>";
        exit;

    } catch (PDOException $e) {
        echo "🔥 Erreur MySQL : " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            width: 320px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        h2 {
            text-align: center;
            color: #007BFF;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Inscription</h2>
    <input type="text" name="phone" placeholder="Numéro de téléphone" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S’inscrire</button>
</form>

</body>
</html>
