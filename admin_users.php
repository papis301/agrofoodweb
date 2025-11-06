<?php
session_start();
require 'db.php';



// Recherche utilisateur
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM usersagrofood WHERE phone LIKE ORDER BY id DESC");
    $stmt->execute([':search' => "%$search%"]);
} else {
    $stmt = $conn->prepare("SELECT * FROM usersagrofood ORDER BY id DESC");
    $stmt->execute();
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
    <?php include 'analytics.php'; ?>
<head>
  <meta charset="UTF-8">
  <title>Gestion des utilisateurs - Agro Food</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link rel="icon" href="assets/img/favicon.png" type="image/png">

  <!-- Bootstrap -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
        background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding-top: 50px;
        font-family: "Open Sans", sans-serif;
    }

    .user-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        border-radius: 15px;
        width: 95%;
        max-width: 1000px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 50px;
    }

    h2 {
        color: #2a7a2e;
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .btn-agro {
        background-color: #2a7a2e;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        transition: 0.3s;
    }

    .btn-agro:hover {
        background-color: #256926;
        transform: scale(1.05);
    }

    th {
        background-color: #2a7a2e;
        color: #fff;
        text-align: center;
    }

    td {
        vertical-align: middle !important;
        text-align: center;
    }

    @media (max-width: 768px) {
        body {
            padding: 20px;
        }
        .user-card {
            padding: 20px;
        }
        table {
            font-size: 14px;
        }
        h2 {
            font-size: 22px;
        }
    }
  </style>
</head>
<body>

<div class="user-card" data-aos="fade-up">
    <div class="text-center mb-4">
        <img src="assets/img/logoagrofoodbon.png" alt="Logo Agro-Food" style="height:80px;">
        <h2>Gestion des utilisateurs</h2>
    </div>

    <form method="get" class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control w-75" placeholder="Rechercher par nom ou numéro...">
        <button class="btn btn-agro ms-2"><i class="bi bi-search"></i></button>
        <a href="dashboard.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left"></i> Retour</a>
    </form>

    <?php if (empty($users)): ?>
        <div class="alert alert-info text-center">
            Aucun utilisateur trouvé.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Téléphone</th>
                        <th>Date d'inscription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['phone']) ?></td>
                        <td><?= htmlspecialchars($u['date_creation'] ?? '') ?></td>
                        <td>
                            <a href="delete_user.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet utilisateur ?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>
