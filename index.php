<?php
require 'db.php';

// Récupérer tous les produits
$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Agro Food</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Marcellus:wght@400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: AgriCulture
  * Template URL: https://It Solution.com/agriculture-bootstrap-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: It Solution.com
  * License: https://It Solution.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center position-relative">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logoagrofoodbon.png" alt="AgriCulture">
        <!-- <h1 class="sitename">AgriCulture</h1>  -->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Accueil</a></li>
           <li><a href="login.php" class="btn btn-success ms-3 px-3 py-1" style="color: white; border-radius: 5px;">Connexion</a></li>
       
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

        

        <div class="carousel-item active">
          <img src="assets/img/hero_2.jpg" alt="">
          <div class="carousel-container">
            </div>
        </div><!-- End Carousel Item -->

        <div class="carousel-item">
          <img src="assets/img/hero_3.jpg" alt="">
          <div class="carousel-container">
            </div>
        </div><!-- End Carousel Item -->


        <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

        <ol class="carousel-indicators"></ol>

      </div>

    </section><!-- /Hero Section -->

    <!-- Services Section -->



    <!-- Services 2 Section -->

    <!-- Testimonials Section -->

    <!-- Recent Posts Section -->
    <section id="recent-posts" class="recent-posts section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Produits disponibles</h2>
        <p></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">

        <?php if (count($products) > 0): ?>
                <?php foreach ($products as $p): ?>
                    <?php
                    $image = "assets/img/no-image.png";
                    if (!empty($p['images'])) {
                        $imgs = json_decode($p['images'], true);
                        if (is_array($imgs) && count($imgs) > 0) {
                            $image = htmlspecialchars($imgs[0]);
                        }
                    }
                    ?>
                    
                
            
          <div class="col-xl-4 col-md-6">
            <div class="post-item position-relative h-100" data-aos="fade-up" data-aos-delay="100">

              <div class="post-img position-relative overflow-hidden">
                <!--<img src="" class="img-fluid" alt="" width="1024px" height="768px">-->
                <img 
                    src="<?= htmlspecialchars($image) ?>" 
                    alt="<?= htmlspecialchars($p['name']) ?>" 
                    class="img-fluid w-100" 
                    style="aspect-ratio: 4/3; object-fit: cover; object-position: center; border-radius: 10px;"
                  >
                <span class="post-date"><?= htmlspecialchars($p['created_at']) ?></span>
              </div>

              <div class="post-content d-flex flex-column">

                <h3 class="post-title"><?= htmlspecialchars($p['name']) ?></h3>

                <div class="meta d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <i class="bi bi-price"></i> <span class="ps-2"><?= htmlspecialchars($p['price']) ?> FCFA</span>
                  </div>
                  <span class="px-3 text-black-50">/</span>
                  <div class="d-flex align-items-center">
                    <i class="bi bi-phone"></i> <span class="ps-2">📞 <?= htmlspecialchars($p['telephone']) ?></span>
                  </div>
                </div>

                <hr>

                <!--<a href="produit.php?id=<?= htmlspecialchars($p['id']) ?>" class="readmore stretched-link"><span>Voir</span><i class="bi bi-arrow-right"></i></a>-->

              </div>

            </div>
          </div><!-- End post item -->
          <?php endforeach; ?>
          <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Aucun produit disponible pour le moment.</p>
                </div>
            <?php endif; ?>

        </div>

      </div>

    </section><!-- /Recent Posts Section -->


  </main>

  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
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
          <div>
            © Copyright <strong><span>Agro Food</span></strong>. All Rights Reserved
          </div>
          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://It Solution.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://It Solution.com/herobiz-bootstrap-business-template/ -->
            Designed by <a href="">It Solution</a>
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>

      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>