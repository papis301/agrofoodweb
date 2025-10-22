<?php
// --- Configuration de la base de données ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "agroweb2";

// Crée la connexion
$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base : " . $conn->connect_error);
}

// Optionnel : définir le jeu de caractères
$conn->set_charset("utf8");
