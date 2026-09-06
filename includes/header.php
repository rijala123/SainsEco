<?php
/**
 * Shared Header Template for Enhanced Eco Clean Game
 * Fullscreen Mobile Native App View
 */
require_once __DIR__ . '/../config/api.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title><?= htmlspecialchars($pageTitle ?? 'Eco Clean Mobile App - Petualangan Lingkungan') ?></title>
  <meta name="description" content="Aplikasi Mobile Petualangan Sains Lingkungan & Game Pilah Sampah Interaktif untuk Anak-Anak.">
  <meta name="theme-color" content="#22C55E">
  
  <!-- PWA Manifest & Icons -->
  <link rel="manifest" href="manifest.json">
  <link rel="apple-touch-icon" href="assets/images/icon-192.png">

  <!-- Google Fonts & FontAwesome -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Main CSS -->
  <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
</head>
<body>

  <!-- Outdoor Nature Environment Background Decor -->
  <div class="nature-bg-decor">
    <div class="sun-glow"></div>
    <div class="cloud cloud1"></div>
    <div class="cloud cloud2"></div>
    <div class="hills-bottom"></div>
  </div>

  <!-- Mobile Native App Container -->
  <div class="mobile-phone-wrapper">
    <div class="mobile-app-screen">

      <?php if (!isset($hideNav) || !$hideNav): ?>
      <!-- Top Mobile App Header Bar -->
      <div class="mobile-app-header">
        <a href="map.php" class="app-brand">
          <div class="app-brand-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div>Sains<span style="color: #22C55E;">Eco</span></div>
        </a>

        <div class="app-user-chip" id="user-badge-display">
          <i class="fas fa-user-circle"></i>
          <span>Pahlawan Eco</span>
        </div>
      </div>
      <?php endif; ?>

      <!-- Scrollable Main Viewport -->
      <div class="mobile-scroll-content">
