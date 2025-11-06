<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'db.php';



// Récupération de tous les utilisateurs
$stmt = $conn->query("SELECT id, phone, statut, date_creation FROM usersagrofood ORDER BY date_creation DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Utilisateurs | Agro Food</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Marcellus&display=swap" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
      background-color: #f7f7f7;
      font-family: 'Open Sans', sans-serif;
    }
    .section-title h2 {
      color: #6ab04c;
      font-weight: 700;
    }
    .table {
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .table thead {
      background-color: #6ab04c;
      color: white;
    }
    .badge-admin {
      background-color: #27ae60;
      color: #fff;
      padding: 5px 10px;
      border-radius: 20px;
    }
    .badge-user {
      background-color: #95a5a6;
      color: #fff;
      padding: 5px 10px;
      border-radius: 20px;
    }
  </style>
</head>

<body class="index-page">

  <!-- Header -->
  <header id="header" class="header d-flex align-items-center position-relative">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logoagrofoodbon.png" alt="Agro Food">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="dashboard.php">Tableau de bord</a></li>
          <li><a href="users.php" class="active">Utilisateurs</a></li>
          <li><a href="logout.php" class="btn btn-danger text-white px-3 py-1" style="border-radius:5px;">Déconnexion</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>
  <!-- End Header -->

  <main class="main">

    <section id="users-list" class="section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Liste des utilisateurs</h2>
        <p>Gérez les comptes enregistrés sur Agro Food</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Numéro de téléphone</th>
                <th>Statut</th>
                <th>Date d’inscription</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td><?= htmlspecialchars($user['statut']) ?></td>
                    
                    <td><?= date("d/m/Y H:i", strtotime($user['date_creation'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-muted">Aucun utilisateur trouvé.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer id="footer" class="footer dark-background">
    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.php" class="logo d-flex align-items-center">
              <span class="sitename">Agro Food</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Dakar en face BCEAO Gibraltar</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+221 76 774 10 08</span></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">
        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>© Copyright <strong><span>Agro Food</span></strong>. All Rights Reserved</div>
          <div class="credits">Designed by <a href="#">It Solution</a></div>
        </div>
        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>
