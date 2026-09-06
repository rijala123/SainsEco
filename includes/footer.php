<?php
/**
 * Shared Footer Template for Enhanced Eco Clean Game
 * Mobile Navigation Bar Component (5 Tabs)
 */
$currentPage = basename($_SERVER['PHP_SELF']);
?>
      </div><!-- /.mobile-scroll-content -->

      <?php if (!isset($hideNav) || !$hideNav): ?>
      <!-- Mobile App Bottom Navigation Bar -->
      <nav class="mobile-bottom-nav" style="grid-template-columns: repeat(5, 1fr);">
        <a href="map.php" class="nav-item-btn <?= ($currentPage === 'map.php' || $currentPage === '' || $currentPage === 'level.php') ? 'active' : '' ?>">
          <i class="fas fa-map-marked-alt"></i>
          <span>Peta</span>
        </a>
        
        <a href="game.php" class="nav-item-btn highlight-play <?= $currentPage === 'game.php' ? 'active' : '' ?>">
          <i class="fas fa-gamepad" style="font-size: 1.3rem;"></i>
          <span>Pilah</span>
        </a>

        <a href="klasemen.php" class="nav-item-btn <?= $currentPage === 'klasemen.php' ? 'active' : '' ?>">
          <i class="fas fa-trophy"></i>
          <span>Skor</span>
        </a>

        <a href="edukasi.php" class="nav-item-btn <?= $currentPage === 'edukasi.php' ? 'active' : '' ?>">
          <i class="fas fa-book-open"></i>
          <span>Belajar</span>
        </a>

        <a href="guru.php" class="nav-item-btn <?= $currentPage === 'guru.php' ? 'active' : '' ?>">
          <i class="fas fa-user-tie"></i>
          <span>Guru</span>
        </a>
      </nav>
      <?php endif; ?>

    </div><!-- /.mobile-app-screen -->
  </div><!-- /.mobile-phone-wrapper -->

  <!-- Core Scripts -->
  <script src="assets/js/main.js"></script>
  <?php if ($currentPage === 'game.php'): ?>
    <script src="assets/js/game.js"></script>
  <?php endif; ?>

  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('service-worker.js')
          .then(reg => console.log('SW Registered'))
          .catch(err => console.log('SW Registration failed:', err));
      });
    }
  </script>

</body>
</html>
