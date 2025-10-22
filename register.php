<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
require 'firebase_config.php';

use Google\Cloud\Core\Timestamp;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Vérification de base
    if (empty($phone) || empty($password)) {
        echo "❌ Tous les champs sont obligatoires.";
        exit;
    }

    try {
        $db = getFirestore();
        $users = $db->collection('usersagrofood');

        // Vérifie si le téléphone existe déjà
        $existing = $users->where('phone', '=', $phone)->documents();
        if (!$existing->isEmpty()) {
            echo "⚠️ Ce numéro existe déjà dans Firestore.";
            exit;
        }

        // Données à enregistrer
        $userData = [
            'phone' => $phone,
            'password' => $password,
            'solde' => 0,
            'statut' => 'activé',
            'date_creation' => date('Y-m-d H:i:s'),
            'type_inscription' => 'ordinateur'
        ];

        // Ajout dans Firestore
        $users->add($userData);

        echo "✅ Inscription réussie ! <a href='login.php'>Se connecter</a>";
        exit;

    } catch (Exception $e) {
        echo "🔥 Erreur Firestore : " . $e->getMessage();
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
