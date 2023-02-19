<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="./main.bundle.css" rel="stylesheet" type="text/css">
  <link href="css/custom.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css2?family=Lexend+Giga&amp;display=swap" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/site.webmanifest">
  <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#777777">
  <meta name="msapplication-TileColor" content="#999999">
  <meta name="theme-color" content="#999999">
  <title><?php echo isset($fichier) ? $fichier : 'Portfolio Neoto'; ?></title>
</head>

<body>
  <header class="fixed-top ">
    <div class="navbar"><a class="navbar-brand" href="index"> <img class="default" alt="Neoto - Creative Studio Portfolio Template" src="images/logo.svg"><img class="inverted" alt="Neoto - Creative Studio Portfolio Template" src="images/logo-white.svg"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"><span class="toggle-label">Menu</span><span class="toggle-label-close">Close</span><span class="toggle-icon"><span class="line"></span><span class="line"></span></span></button>
    </div>
    <ul id="nav">
      <!-- class="active" => voir pour mettre dynamiquement sur li -->
      <li class="nav-item"><a class="nav-link" href="index">home</a></li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">pages</a>
        <div class="dropdown-menu"><a class="dropdown-item" href="about">About</a><a class="dropdown-item" href="services">Services</a><a class="dropdown-item" href="404">404</a></div>
      </li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">portfolio</a>
        <div class="dropdown-menu"><a class="dropdown-item" href="projects-grid">Projects - Grid</a><a class="dropdown-item" href="projects-slider">Projects - Slider</a><a class="dropdown-item" href="projects-lightbox">Projects - Lightbox</a><a class="dropdown-item" href="project-single">Project single</a><a class="dropdown-item" href="project-single-hero">Project single - Hero</a><a class="dropdown-item" href="project-single-alt">Project single - Alt</a></div>
      </li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">blog</a>
        <div class="dropdown-menu"><a class="dropdown-item" href="blog-slider">Blog - Slider</a><a class="dropdown-item" href="blog-grid">Blog - Grid</a><a class="dropdown-item" href="blog-single">Blog single</a><a class="dropdown-item" href="blog-single-alt">Blog single - Alt</a><a class="dropdown-item" href="blog-single-hero">Blog single - Hero</a></div>
      </li>
      <li class="nav-item"><a class="nav-link" href="contact">contact</a></li>
    </ul>
  </header>

  <!-- début contenu 'main' -->  
  <?php echo isset($content_for_layout) ? $content_for_layout : ''; ?>
  <!-- fin contenu 'main' -->

  <footer>
    <?php if ($fichier !== 'index') : ?>
      <div class="container-fluid p-5">
        <div class="d-flex justify-content-between align-items-center">
          <div class="social"><a class="trigger" href="#">Follow</a>
            <ul class="social-nav">
              <li> <a href="#">Instagram</a></li>
              <li> <a href="#">Twitter</a></li>
              <li> <a href="#">Vimeo</a></li>
              <li> <a href="#">YouTube</a></li>
            </ul>
          </div>
          <div class="copy">2020 &copy; YourBrand. All rights reserved</div>
          <a class="to-top" href="#">
            <svg class="bi" width="32" height="32" fill="currentColor">
              <use xlink:href="Medias/images/bootstrap-icons.svg#chevron-compact-up"></use>
            </svg>
          </a>
        </div>
      </div>
  </footer>
<?php endif; ?>
<script src="./main.bundle.js"></script>

</body>

</html>