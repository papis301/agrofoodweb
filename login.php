<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'firebase_config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($phone) || empty($password)) {
        echo "❌ Tous les champs sont obligatoires.";
        exit;
    }

    $db = getFirestore();
    $users = $db->collection('usersagrofood')->where('phone', '=', $phone)->documents();

    if ($users->isEmpty()) {
        echo "❌ Ce numéro n'existe pas.";
        exit;
    }

    foreach ($users as $user) {
        $data = $user->data();
        if ($password === $data['password']) {

            // Vérifie le statut
            if ($data['statut'] !== 'activé') {
                echo "⚠️ Votre compte est désactivé.";
                exit;
            }

            // ✅ Stocker toutes les infos utiles en session
            $_SESSION['user_phone']   = $data['phone'];              // téléphone
            $_SESSION['solde']        = $data['solde'] ?? 0;         // solde
            $_SESSION['statut']       = $data['statut'];             // statut
            $_SESSION['firebase_id']  = $user->id();                 // ✅ ID Firestore du document
            $_SESSION['user_name']    = $data['name'] ?? '';         // optionnel : nom utilisateur

            // Redirection vers le tableau de bord
            header("Location: dashboard.php");
            exit;
        } else {
            echo "❌ Mot de passe incorrect.";
            exit;
        }
    }
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
