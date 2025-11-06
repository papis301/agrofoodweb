<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'db.php';
session_start();

// ✅ Sécurité : si t’es pas connecté, on dégage vers la page de login
if (!isset($_SESSION['user_phone'])) {
  header("Location: login.php");
  exit;
}

// ✅ Suppression d’un produit
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
  $stmt->execute([':id' => $id]);
  header("Location: admin_products.php?deleted=1");
  exit;
}

// ✅ Récupération des produits
$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'analytics.php'; ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Gestion des Produits - AgroFood</title>

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <!-- 🔹 HEADER -->
  <header id="header" class="header d-flex align-items-center position-relative">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logoagrofoodbon.png" alt="">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="dashboard.php" class="active">Tableau de bord</a></li>
          <li><a href="logout.php" class="btn btn-danger text-white px-3">Déconnexion</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main" style="margin-top: 100px;">

    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Gestion des produits</h2>
        <p>Visualisez, gérez et supprimez les produits enregistrés</p>
      </div>

      <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success text-center">✅ Produit supprimé avec succès !</div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-striped align-middle text-center">
          <thead class="table-success">
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Nom</th>
              <th>Prix</th>
              <th>Téléphone</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (count($products) > 0): ?>
            <?php foreach ($products as $p): ?>
              <?php
              $image = "assets/img/no-image.png";
              if (!empty($p['images'])) {
                  $imgs = json_decode($p['images'], true);
                  if (is_array($imgs) && count($imgs) > 0 && !empty($imgs[0])) {
                      $image = (string)$imgs[0];
                  }
              }

              $id = (string)($p['id'] ?? '');
              $name = (string)($p['name'] ?? '');
              $price = (string)($p['price'] ?? '');
              $tel = (string)($p['telephone'] ?? '');
              $created = (string)($p['created_at'] ?? '');
              ?>
              <tr>
                <td><?= htmlspecialchars($id) ?></td>
                <td>
                  <img src="<?= htmlspecialchars($image) ?>" alt="image" width="70" height="70" style="object-fit: cover; border-radius: 8px;">
                </td>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($price) ?> FCFA</td>
                <td><?= htmlspecialchars($tel) ?></td>
                <td><?= htmlspecialchars($created) ?></td>
                <td>
                  <a href="produit.php?id=<?= urlencode($id) ?>" class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye"></i> Voir
                  </a>
                  <a href="?delete=<?= urlencode($id) ?>" class="btn btn-outline-danger btn-sm"
                     onclick="return confirm('Supprimer ce produit ?');">
                    <i class="bi bi-trash"></i> Supprimer
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-muted">Aucun produit disponible pour le moment</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- 🔹 FOOTER -->
  <footer id="footer" class="footer dark-background">
    <div class="copyright text-center py-3">
      <p>© <strong>AgroFood</strong> — Tous droits réservés | Design by It Solution</p>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
