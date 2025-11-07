<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM product_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes recherches - Agro Food</title>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <?php include 'header.php'; ?>

  <main class="main">
    <section class="section">
      <div class="container mt-5">
        <div class="section-title" data-aos="fade-up">
          <h2>🛍️ Mes recherches</h2>
        </div>

        <div class="row gy-4">
          <?php foreach ($requests as $req): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                  <h5><?= htmlspecialchars($req['title']) ?></h5>
                  <p><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                  <p><strong>📞 Contact :</strong> <?= htmlspecialchars($req['telephone']) ?></p>
                  <?php
                    $images = json_decode($req['images'], true);
                    if (!empty($images)):
                  ?>
                    <div id="carousel<?= $req['id'] ?>" class="carousel slide" data-bs-ride="carousel">
                      <div class="carousel-inner">
                        <?php foreach ($images as $index => $img): ?>
                          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= $img ?>" class="d-block w-100" style="height:200px; object-fit:cover;">
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $req['id'] ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                      </button>
                      <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $req['id'] ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                      </button>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
