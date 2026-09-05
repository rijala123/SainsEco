<?php
$pageTitle = "Peta Petualangan - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🗺️ Peta Petualangan Eco</h2>
      <p>Selesaikan 5 level sains lingkungan!</p>
    </div>
    <div style="background: rgba(255,255,255,0.25); border: 2px solid #FFF; padding: 0.4rem 0.8rem; border-radius: 20px; text-align: center;">
      <div style="font-size: 0.7rem; font-weight: 800;">TOTAL BINTANG</div>
      <div style="font-size: 1.25rem; font-weight: 900;" id="total-stars-count">0 ⭐</div>
    </div>
  </div>
</div>

<!-- Adventure Map Trail Box -->
<div class="map-trail-container" style="position: relative; background: linear-gradient(180deg, #BAE6FD 0%, #DCFCE7 50%, #86EFAC 100%); border: 3px solid #22C55E; border-radius: 24px; padding: 1.5rem 1rem; box-shadow: var(--shadow-cartoon); overflow: hidden; min-height: 480px;">

  <!-- Winding Path Line (SVG) -->
  <svg style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;" viewBox="0 0 350 480" preserveAspectRatio="none">
    <path d="M 175 420 C 280 380, 280 320, 175 280 C 70 240, 70 180, 175 140 C 280 100, 280 60, 175 40" fill="none" stroke="#FDE047" stroke-width="12" stroke-linecap="round" stroke-dasharray="16 10"/>
    <path d="M 175 420 C 280 380, 280 320, 175 280 C 70 240, 70 180, 175 140 C 280 100, 280 60, 175 40" fill="none" stroke="#F59E0B" stroke-width="4" stroke-linecap="round"/>
  </svg>

  <!-- Level 5 Node (Top) -->
  <div class="level-node-item" id="node-level-5" style="position: relative; z-index: 5; margin-bottom: 2rem; display: flex; justify-content: center;">
    <a href="level5.php" class="level-btn locked" data-level="5">
      <div class="level-badge">LEVEL 5</div>
      <div class="level-icon">🌟</div>
      <div class="level-title">Tindakan Nyata</div>
      <div class="level-subtitle">Decision Game</div>
      <div class="level-stars" id="stars-level-5">☆☆☆</div>
    </a>
  </div>

  <!-- Level 4 Node -->
  <div class="level-node-item" id="node-level-4" style="position: relative; z-index: 5; margin-bottom: 2rem; display: flex; justify-content: flex-end; padding-right: 1.5rem;">
    <a href="level4.php" class="level-btn locked" data-level="4">
      <div class="level-badge">LEVEL 4</div>
      <div class="level-icon">🔢</div>
      <div class="level-title">Urutan Langkah</div>
      <div class="level-subtitle">Sequencing Game</div>
      <div class="level-stars" id="stars-level-4">☆☆☆</div>
    </a>
  </div>

  <!-- Level 3 Node -->
  <div class="level-node-item" id="node-level-3" style="position: relative; z-index: 5; margin-bottom: 2rem; display: flex; justify-content: flex-start; padding-left: 1.5rem;">
    <a href="level3.php" class="level-btn locked" data-level="3">
      <div class="level-badge">LEVEL 3</div>
      <div class="level-icon">🔍</div>
      <div class="level-title">Fokus Inti</div>
      <div class="level-subtitle">Filter Solusi</div>
      <div class="level-stars" id="stars-level-3">☆☆☆</div>
    </a>
  </div>

  <!-- Level 2 Node -->
  <div class="level-node-item" id="node-level-2" style="position: relative; z-index: 5; margin-bottom: 2rem; display: flex; justify-content: flex-end; padding-right: 1.5rem;">
    <a href="level2.php" class="level-btn locked" data-level="2">
      <div class="level-badge">LEVEL 2</div>
      <div class="level-icon">🗂️</div>
      <div class="level-title">Memecahkan Masalah</div>
      <div class="level-subtitle">Grouping Game</div>
      <div class="level-stars" id="stars-level-2">☆☆☆</div>
    </a>
  </div>

  <!-- Level 1 Node (Bottom Start) -->
  <div class="level-node-item" id="node-level-1" style="position: relative; z-index: 5; display: flex; justify-content: center;">
    <a href="level1.php" class="level-btn active-unlocked" data-level="1">
      <div class="level-badge">LEVEL 1</div>
      <div class="level-icon">🧩</div>
      <div class="level-title">Pengenalan Pola</div>
      <div class="level-subtitle">Matching Game</div>
      <div class="level-stars" id="stars-level-1">☆☆☆</div>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const userKey = currentUser.key_code || 'ECO-GUEST';

  fetch(`api/index.php?action=get_map_progress&access_key=${encodeURIComponent(userKey)}`)
    .then(res => res.json())
    .then(res => {
      if (res.success && res.levels) {
        let totalStars = 0;
        res.levels.forEach(lvl => {
          const btn = document.querySelector(`.level-btn[data-level="${lvl.level_number}"]`);
          const starsEl = document.getElementById(`stars-level-${lvl.level_number}`);

          totalStars += lvl.stars;

          // Render Stars
          let starStr = '';
          for (let s = 1; s <= 3; s++) {
            starStr += (s <= lvl.stars) ? '⭐' : '☆';
          }
          if (starsEl) starsEl.textContent = starStr;

          // Update Lock / Unlock state
          if (btn) {
            if (lvl.unlocked) {
              btn.classList.remove('locked');
              btn.classList.add('active-unlocked');
            } else {
              btn.classList.add('locked');
              btn.classList.remove('active-unlocked');
              btn.addEventListener('click', (e) => {
                e.preventDefault();
                showToast(`Level ${lvl.level_number} masih terkunci! Selesaikan level sebelumnya lebih dulu.`, 'warning');
              });
            }
          }
        });

        document.getElementById('total-stars-count').textContent = `${totalStars} ⭐`;
      }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
