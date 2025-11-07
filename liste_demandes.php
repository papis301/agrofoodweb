<?php
require 'db.php';
session_start();

$stmt = $conn->query("SELECT * FROM demandes ORDER BY date_creation DESC");
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Demandes de produits - Agro Food</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body {
      background: url('assets/img/hero_2.jpg') center/cover no-repeat fixed;
      min-height: 100vh;
      font-family: "Open Sans", sans-serif;
    }

    .page-section {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 40px 25px;
      margin-top: 100px;
      box-shadow: 0 4px 25px rgba(0,0,0,0.1);
    }

    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: scale(1.02);
    }

    .card-body {
      padding: 20px;
    }

    .carousel-item img {
      width: 100%;
      height: 250px;
      object-fit: cover;
    }

    .text-success {
      color: #2a7a2e !important;
    }

    @media (max-width: 768px) {
      .page-section {
        margin-top: 70px;
        padding: 20px;
      }
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="container page-section" data-aos="fade-up">
    <div class="text-center mb-4">
      <img src="assets/img/logoagrofoodbon.png" alt="Logo" style="height:80px;">
      <h2 class="fw-bold text-success mt-2">Demandes de produits</h2>
    </div>

    <?php if (count($demandes) > 0): ?>
      <div class="row">
        <?php foreach ($demandes as $d): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm">
              <?php
              $images = json_decode($d['images'] ?? "[]", true);
              if (!empty($images)) {
                  $carouselId = "carousel_" . $d['id'];
                  echo '<div id="'.$carouselId.'" class="carousel slide" data-bs-ride="carousel">';
                  echo '<div class="carousel-inner">';
                  foreach ($images as $index => $img) {
                      $active = $index === 0 ? 'active' : '';
                      $imgPath = htmlspecialchars($img);
                      echo '<div class="carousel-item '.$active.'">';
                      echo '<img src="'.$imgPath.'" alt="Image" class="d-block w-100">';
                      echo '</div>';
                  }
                  echo '</div>';
                  echo '<button class="carousel-control-prev" type="button" data-bs-target="#'.$carouselId.'" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#'.$carouselId.'" data-bs-slide="next">
                          <span class="carousel-control-next-icon"></span>
                        </button>';
                  echo '</div>';
              }
              ?>

              <div class="card-body">
                <h5 class="card-title text-success"><?= htmlspecialchars($d['produit_recherche']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($d['description'])) ?></p>
                <p class="text-muted mb-1"><i class="bi bi-person-circle"></i> Posté par : <strong><?= htmlspecialchars($d['user_phone']) ?></strong></p>
                <small class="text-secondary"><i class="bi bi-clock"></i> <?= htmlspecialchars($d['date_creation']) ?></small>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info text-center">
        Aucune demande publiée pour le moment.<br>
        <a href="ajouter_demande.php" class="btn btn-success mt-2"><i class="bi bi-plus-circle"></i> Faire une demande</a>
      </div>
    <?php endif; ?>
  </div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script>AOS.init();</script>
</body>
</html>
