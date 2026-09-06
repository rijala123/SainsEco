<?php
$pageTitle = "Klasemen Pahlawan Eco - Eco Clean App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #F59E0B, #D97706); box-shadow: 0 6px 0 #B45309;">
  <div style="display: flex; align-items: center; gap: 0.75rem;">
    <div style="font-size: 2.2rem;">🏆</div>
    <div>
      <h2>Klasemen Pahlawan!</h2>
      <p>Total skor Game Pilah + Level 1–5 Petualangan</p>
    </div>
  </div>
</div>

<!-- Podium HP Cards -->
<div id="podium-section" class="hp-podium">
  <div style="text-align: center; color: var(--text-muted); padding: 1rem; width: 100%;">
    <i class="fas fa-spinner fa-spin"></i> Memuat podium...
  </div>
</div>

<!-- Score Legend -->
<div class="kid-card" style="padding: 0.65rem 0.85rem; margin-bottom: 0.75rem;">
  <div style="font-size: 0.72rem; color: var(--text-muted); display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
    <span>🎮 <strong style="color:#22C55E;">Game Pilah</strong> = maks skor terbaik</span>
    <span>🗺️ <strong style="color:#0284C7;">Level 1–5</strong> = jumlah skor bintang</span>
    <span>⭐ <strong style="color:#F59E0B;">Total</strong> = Gabungan keduanya</span>
  </div>
</div>

<!-- Search Bar -->
<div style="margin-bottom: 1rem;">
  <input type="text" id="leaderboard-search" class="kid-form-control"
    placeholder="🔍 Cari nama pahlawan..." style="background: #FFFFFF;">
</div>

<!-- Leaderboard Cards List -->
<div id="leaderboard-list-container">
  <!-- Rendered via JS -->
</div>

<!-- Refresh Button -->
<div style="text-align: center; margin-top: 0.5rem;">
  <button class="btn-kid btn-kid-blue" id="btn-refresh" style="width: auto; padding: 0.5rem 1.2rem; font-size: 0.82rem;">
    <i class="fas fa-sync-alt"></i> Perbarui Klasemen
  </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const searchInput   = document.getElementById('leaderboard-search');
  const btnRefresh    = document.getElementById('btn-refresh');

  function fetchLeaderboard(searchQuery = '') {
    fetch(`api/index.php?action=get_leaderboard&search=${encodeURIComponent(searchQuery)}`)
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          renderPodium(res.data.slice(0, 3));
          renderList(res.data);
        } else {
          document.getElementById('leaderboard-list-container').innerHTML =
            `<div class="kid-card" style="text-align:center;color:#EF4444;padding:1rem;">
              <i class="fas fa-exclamation-triangle"></i> Gagal memuat klasemen.
            </div>`;
        }
      })
      .catch(() => {
        document.getElementById('leaderboard-list-container').innerHTML =
          `<div class="kid-card" style="text-align:center;color:#EF4444;padding:1rem;">
            <i class="fas fa-wifi"></i> Koneksi gagal. Pastikan server aktif.
          </div>`;
      });
  }

  function renderPodium(top3) {
    const podiumEl = document.getElementById('podium-section');
    if (!podiumEl) return;

    if (!top3 || top3.length === 0) {
      podiumEl.innerHTML = `
        <div class="kid-card" style="width:100%;text-align:center;padding:1.5rem;background:#FFFFFF;margin:0;">
          <div style="font-size:2.5rem;margin-bottom:0.3rem;">🌱🏆</div>
          <div style="font-weight:800;font-size:0.95rem;color:var(--text-dark);">Klasemen Masih Kosong</div>
          <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:0.85rem;">
            Jadilah pahlawan pertama! Selesaikan level atau game pilah sampah!
          </div>
          <a href="map.php" class="btn-kid btn-kid-green" style="display:inline-flex;width:auto;padding:0.5rem 1.2rem;font-size:0.85rem;">
            <i class="fas fa-map-marked-alt"></i> Mulai Petualangan
          </a>
        </div>
      `;
      return;
    }

    const crowns = ['🥇', '🥈', '🥉'];
    let html = '';
    top3.forEach((item, idx) => {
      const rankNum = idx + 1;
      html += `
        <div class="hp-podium-card rank-${rankNum}">
          <div style="font-size:1.6rem;line-height:1;">${crowns[idx]}</div>
          <div style="font-weight:800;font-size:0.82rem;margin-top:0.25rem;
               white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            ${escapeHtml(item.student_name)}
          </div>
          <div style="font-size:0.68rem;color:var(--text-muted);">${escapeHtml(item.school_class)}</div>
          <div style="font-size:1rem;font-weight:900;color:#15803D;margin-top:0.2rem;">
            ${item.total_score} <span style="font-size:0.65rem;">pts</span>
          </div>
          <div style="font-size:0.6rem;color:#0284C7;margin-top:0.1rem;">
            ${item.levels_done}/5 Level ⭐
          </div>
        </div>
      `;
    });
    podiumEl.innerHTML = html;
  }

  function renderList(data) {
    const container = document.getElementById('leaderboard-list-container');
    if (!container) return;

    if (!data || data.length === 0) {
      container.innerHTML = `
        <div class="kid-card" style="text-align:center;color:var(--text-muted);padding:1.25rem;">
          <div style="font-size:2rem;margin-bottom:0.5rem;">🌿</div>
          <strong>Belum ada pahlawan tercatat.</strong><br>
          <span style="font-size:0.8rem;">Selesaikan level atau game pilah sampah untuk masuk ke sini!</span><br><br>
          <a href="map.php" class="btn-kid btn-kid-green" style="width:auto;padding:0.5rem 1.2rem;font-size:0.82rem;">
            <i class="fas fa-play"></i> Mulai Sekarang
          </a>
        </div>
      `;
      return;
    }

    let html = '';
    data.forEach(row => {
      let rankBg    = '#DCFCE7';
      let rankColor = '#15803D';
      if (row.rank == 1) { rankBg = '#FEF3C7'; rankColor = '#B45309'; }
      if (row.rank == 2) { rankBg = '#E0F2FE'; rankColor = '#0369A1'; }
      if (row.rank == 3) { rankBg = '#FEE2E2'; rankColor = '#B91C1C'; }

      const levelBar = [];
      for (let i = 1; i <= 5; i++) {
        levelBar.push(i <= parseInt(row.levels_done) ? '⭐' : '☆');
      }

      html += `
        <div class="kid-card" style="padding:0.85rem 1rem;margin-bottom:0.6rem;">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.6rem;flex:1;min-width:0;">
              <div style="min-width:32px;height:32px;background:${rankBg};color:${rankColor};
                   border-radius:50%;display:flex;align-items:center;justify-content:center;
                   font-weight:900;font-size:0.85rem;flex-shrink:0;">
                #${row.rank}
              </div>
              <div style="min-width:0;">
                <div style="font-weight:800;font-size:0.9rem;color:var(--text-dark);
                     white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  ${escapeHtml(row.student_name)}
                </div>
                <div style="font-size:0.7rem;color:var(--text-muted);">
                  ${escapeHtml(row.school_class)}
                </div>
                <div style="font-size:0.68rem;margin-top:0.15rem;">
                  <span style="color:#F59E0B;">${levelBar.join('')}</span>
                  &nbsp;
                  <span style="color:#0284C7;font-weight:700;">${escapeHtml(row.badge_title)}</span>
                </div>
              </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
              <div style="font-weight:900;font-size:1.15rem;color:#22C55E;">${row.total_score} <span style="font-size:0.7rem;">pts</span></div>
              <div style="font-size:0.65rem;color:var(--text-muted);font-weight:800;">TOTAL GABUNGAN</div>
              <div style="font-size:0.68rem;color:#0284C7;font-weight:700;margin-top:0.1rem;">
                🎮 ${row.game_score} + 🗺️ ${row.level_score}
              </div>
            </div>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
  }

  let debounceTimeout;
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      clearTimeout(debounceTimeout);
      debounceTimeout = setTimeout(() => fetchLeaderboard(e.target.value.trim()), 300);
    });
  }

  if (btnRefresh) {
    btnRefresh.addEventListener('click', () => {
      btnRefresh.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memperbarui...';
      fetchLeaderboard(searchInput ? searchInput.value.trim() : '');
      setTimeout(() => {
        btnRefresh.innerHTML = '<i class="fas fa-sync-alt"></i> Perbarui Klasemen';
      }, 1000);
    });
  }

  fetchLeaderboard();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
