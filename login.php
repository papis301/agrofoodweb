<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (empty($phone) || empty($password)) {
        $error = "❌ Tous les champs sont obligatoires.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM usersagrofood WHERE phone = :phone LIMIT 1");
        $stmt->execute([':phone' => $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Création de la session
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_id'] = $user['id']; // Optionnel
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "⚠️ Numéro ou mot de passe incorrect.";
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
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        h2 { text-align: center; color: #007BFF; }
        p.error { color: red; text-align: center; }
    </style>
</head>
<body>

<form method="POST">
    <h2>Connexion</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <input type="text" name="phone" placeholder="Numéro de téléphone" required>
    <input type="password" name="password" placeholder="Mot de passe" required>

    <button type="submit">Se connecter</button>
    <p style="text-align:center;">
        Pas encore de compte ? <a href="register.php">S’inscrire</a>
    </p>
</form>

</body>
</html>
