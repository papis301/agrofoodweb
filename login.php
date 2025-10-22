<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'firebase_config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    $projectId = "its2025"; // 🔹 remplace par ton vrai ID
    $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/usersagrofood";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("❌ Erreur de connexion à Firestore (REST).");
    }

    $data = json_decode($response, true);

    if (!isset($data['documents'])) {
        die("❌ Aucun utilisateur trouvé (vérifie la collection Firestore).");
    }

    foreach ($data['documents'] as $doc) {
        $fields = $doc['fields'];
        $dbPhone = $fields['phone']['stringValue'] ?? '';
        $dbPassword = $fields['password']['stringValue'] ?? '';
        $statut = $fields['statut']['stringValue'] ?? 'désactivé';
        $solde = $fields['solde']['integerValue'] ?? 0;
        $firebase_id = basename($doc['name']);

        if ($dbPhone === $phone) {
            if ($password === $dbPassword) {
                if ($statut !== 'activé') {
                    die("⚠️ Compte désactivé.");
                }

                // ✅ Connexion réussie
                $_SESSION['user_phone'] = $dbPhone;
                $_SESSION['solde'] = $solde;
                $_SESSION['statut'] = $statut;
                $_SESSION['firebase_id'] = $firebase_id;

                header("Location: dashboard.php");
                exit;
            } else {
                die("❌ Mot de passe incorrect.");
            }
        }
    }

    echo "❌ Utilisateur introuvable.";
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 10px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        h2 { text-align: center; color: #007BFF; }
    </style>
</head>
<body>

<form method="POST">
    <h2>Connexion</h2>
    <input type="text" name="phone" placeholder="Numéro de téléphone" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>

</body>
</html>
