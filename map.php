<?php
$pageTitle = "Peta Petualangan - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🗺️ Peta Petualangan Eco</h2>
      <p id="map-subtitle">Selesaikan level sains lingkungan!</p>
    </div>
    <div style="background: rgba(255,255,255,0.25); border: 2px solid #FFF; padding: 0.4rem 0.8rem; border-radius: 20px; text-align: center;">
      <div style="font-size: 0.7rem; font-weight: 800;">TOTAL BINTANG</div>
      <div style="font-size: 1.25rem; font-weight: 900;" id="total-stars-count">0 ⭐</div>
    </div>
  </div>
</div>

<!-- Adventure Map Trail Container -->
<div class="map-trail-container" style="position: relative; background: linear-gradient(180deg, #BAE6FD 0%, #DCFCE7 50%, #86EFAC 100%); border: 3px solid #22C55E; border-radius: 24px; padding: 1.5rem 1rem; box-shadow: var(--shadow-cartoon); overflow: hidden; min-height: 480px;">

  <!-- Winding Path Line (SVG) -->
  <svg style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1;" viewBox="0 0 350 480" preserveAspectRatio="none">
    <path d="M 175 420 C 280 380, 280 320, 175 280 C 70 240, 70 180, 175 140 C 280 100, 280 60, 175 40" fill="none" stroke="#FDE047" stroke-width="12" stroke-linecap="round" stroke-dasharray="16 10"/>
    <path d="M 175 420 C 280 380, 280 320, 175 280 C 70 240, 70 180, 175 140 C 280 100, 280 60, 175 40" fill="none" stroke="#F59E0B" stroke-width="4" stroke-linecap="round"/>
  </svg>

  <div id="map-nodes-list" style="position: relative; z-index: 5; display: flex; flex-direction: column-reverse; gap: 2rem;">
    <!-- Rendered dynamically via JS from DB -->
    <div style="text-align: center; padding: 3rem; color: var(--text-muted); font-weight: 800;">
      Memuat Peta Petualangan... ⏳
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const userKey = currentUser.key_code || 'ECO-GUEST';

  // Load levels & user progress simultaneously
  Promise.all([
    fetch('api/index.php?action=get_levels').then(r => r.json()),
    fetch(`api/index.php?action=get_map_progress&access_key=${encodeURIComponent(userKey)}`).then(r => r.json())
  ])
  .then(([levelsRes, progressRes]) => {
    const levels = levelsRes.data || [];
    const userProgress = progressRes.levels || [];
    
    if (levels.length > 0) {
      document.getElementById('map-subtitle').textContent = `Selesaikan ${levels.length} level sains lingkungan!`;
      renderMapNodes(levels, userProgress);
    } else {
      document.getElementById('map-nodes-list').innerHTML = `
        <div class="kid-card" style="text-align: center; margin: 2rem 0;">
          <div style="font-size: 3rem;">🧩</div>
          <h3>Belum Ada Level</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted);">Guru belum menambahkan level peta.</p>
        </div>
      `;
    }
  })
  .catch(err => console.error(err));

  function renderMapNodes(levels, userProgress) {
    const container = document.getElementById('map-nodes-list');
    let totalStars = 0;
    const progressMap = {};
    userProgress.forEach(p => { progressMap[p.level_number] = p; });

    // Alignment layout positions: center, right, left, right, center
    const alignMap = ['center', 'flex-end', 'flex-start', 'flex-end', 'center'];

    container.innerHTML = levels.map((lvl, index) => {
      const p = progressMap[lvl.level_number] || { stars: 0, unlocked: (lvl.level_number === 1) };
      if (p.unlocked === undefined) p.unlocked = (lvl.level_number === 1);

      totalStars += (p.stars || 0);

      let starStr = '';
      for (let s = 1; s <= 3; s++) {
        starStr += (s <= p.stars) ? '⭐' : '☆';
      }

      const alignPos = alignMap[index % alignMap.length];
      const paddingSide = alignPos === 'flex-end' ? 'padding-right: 1.5rem;' : (alignPos === 'flex-start' ? 'padding-left: 1.5rem;' : '');

      return `
        <div class="level-node-item" style="display: flex; justify-content: ${alignPos}; ${paddingSide}">
          <a href="level.php?id=${lvl.level_number}" class="level-btn ${p.unlocked ? 'active-unlocked' : 'locked'}" data-level="${lvl.level_number}">
            <div class="level-badge" style="background: ${lvl.bg_color || '#22C55E'};">LEVEL ${lvl.level_number}</div>
            <div class="level-icon">${lvl.icon || '🧩'}</div>
            <div class="level-title">${escapeHtml(lvl.title.replace(/^Level \d+:\s*/i, ''))}</div>
            <div class="level-subtitle">${escapeHtml(lvl.subtitle || '')}</div>
            <div class="level-stars">${starStr}</div>
          </a>
        </div>
      `;
    }).join('');

    document.getElementById('total-stars-count').textContent = `${totalStars} ⭐`;

    // Prevent clicking locked buttons
    container.querySelectorAll('.level-btn.locked').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const num = btn.getAttribute('data-level');
        showToast(`Level ${num} masih terkunci! Selesaikan level sebelumnya lebih dulu.`, 'warning');
      });
    });
  }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
