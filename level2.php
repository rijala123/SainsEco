<?php
$pageTitle = "Level 2: Memecahkan Masalah - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #0284C7, #0369A1);">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🗂️ Level 2: Grouping Game</h2>
      <p>Kelompokkan penyebab ke dalam 3 jenis pencemaran!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Current Item Active Box -->
<div class="kid-card" style="text-align: center; border-color: #0284C7; margin-bottom: 1rem; background: #E0F2FE;">
  <div style="font-size: 0.75rem; font-weight: 800; color: #0369A1; margin-bottom: 0.3rem;">ITEM PENYEBAB SAAT INI:</div>
  <div id="active-item-title" style="font-size: 1.1rem; font-weight: 900; color: var(--text-dark);">
    Memuat Item...
  </div>
  <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.3rem;">Pilih Kategori Pencemaran Yang Sesuai Di Bawah!</div>
</div>

<!-- 3 Target Group Containers -->
<div style="display: flex; flex-direction: column; gap: 0.75rem;">
  <div class="kid-card group-bin" data-cat="air" style="border-color: #0284C7; cursor: pointer; display: flex; align-items: center; gap: 1rem; margin: 0; padding: 0.9rem;">
    <div style="font-size: 2rem;">🌊</div>
    <div>
      <h3 style="font-size: 1.05rem; color: #0369A1;">Pencemaran Air</h3>
      <p style="font-size: 0.75rem; color: var(--text-muted);">Sungai, Danau, & Lautan</p>
    </div>
  </div>

  <div class="kid-card group-bin" data-cat="udara" style="border-color: #64748B; cursor: pointer; display: flex; align-items: center; gap: 1rem; margin: 0; padding: 0.9rem;">
    <div style="font-size: 2rem;">💨</div>
    <div>
      <h3 style="font-size: 1.05rem; color: #334155;">Pencemaran Udara</h3>
      <p style="font-size: 0.75rem; color: var(--text-muted);">Atmosfer & Udara Pernapasan</p>
    </div>
  </div>

  <div class="kid-card group-bin" data-cat="tanah" style="border-color: #15803D; cursor: pointer; display: flex; align-items: center; gap: 1rem; margin: 0; padding: 0.9rem;">
    <div style="font-size: 2rem;">🏞️</div>
    <div>
      <h3 style="font-size: 1.05rem; color: #15803D;">Pencemaran Tanah</h3>
      <p style="font-size: 0.75rem; color: var(--text-muted);">Lahan, Hutan, & Resapan Air</p>
    </div>
  </div>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🗂️🎉</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 2 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Kamu berhasil mengelompokkan masalah pencemaran lingkungan!</p>

  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.2rem; font-weight: 800; color: #22C55E; margin-bottom: 1rem;">Skor: <span id="res-score">0</span></div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Ke Peta
    </a>
    <a href="level3.php" class="btn-kid btn-kid-green" style="flex: 1;">
      Lanjut Level 3 <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const itemsDB = [
    { title: '🧪 Pembuangan Limbah Cair Kimia Pabrik', cat: 'air' },
    { title: '🏭 Asap Cerobong Kilang Gas Industri', cat: 'udara' },
    { title: '🔋 Penimbunan Baterai & Limbah B3 di Tanah', cat: 'tanah' },
    { title: '⚓ Tumpahan Minyak Kapal Tanker di Laut', cat: 'air' },
    { title: '🚗 Gas Buang Karbon Monoksida Knalpot', cat: 'udara' },
    { title: '🛍️ Penguburan Sampah Plastik yang Tak Terurai', cat: 'tanah' }
  ];

  let currentIndex = 0;
  let errorsCount = 0;

  const itemTitleEl = document.getElementById('active-item-title');
  const groupBins = document.querySelectorAll('.group-bin');

  function renderCurrentItem() {
    if (currentIndex >= itemsDB.length) {
      finishLevel();
      return;
    }

    const item = itemsDB[currentIndex];
    itemTitleEl.textContent = `${currentIndex + 1}/${itemsDB.length}. ${item.title}`;
  }

  groupBins.forEach(bin => {
    bin.addEventListener('click', () => {
      if (currentIndex >= itemsDB.length) return;

      const chosenCat = bin.getAttribute('data-cat');
      const actualCat = itemsDB[currentIndex].cat;

      if (chosenCat === actualCat) {
        ecoSound.playCorrect();
        showToast('Tepat sekali! Kelompok masalah sesuai.', 'success');
        currentIndex++;
        renderCurrentItem();
      } else {
        ecoSound.playWrong();
        errorsCount++;
        showToast(`Oops! Barang ini masuk kategori ${actualCat.toUpperCase()}!`, 'warning');
      }
    });
  });

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (errorsCount >= 3) stars = 1;
    else if (errorsCount >= 1) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = 500 - (errorsCount * 40);
    document.getElementById('res-score').textContent = finalScore;

    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: currentUser.key_code || 'ECO-GUEST',
        level_number: 2,
        stars_earned: stars,
        score: finalScore
      })
    });

    document.getElementById('modal-level-result').style.display = 'flex';
  }

  renderCurrentItem();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
