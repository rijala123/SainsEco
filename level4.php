<?php
$pageTitle = "Level 4: Urutan Langkah - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #7C3AED, #6D28D9); box-shadow: 0 8px 0 #5B21B6;">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🔢 Level 4: Sequencing Game</h2>
      <p>Susun langkah penyelesaian secara berurutan!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<div class="kid-card" style="padding: 0.85rem; margin-bottom: 1rem;">
  <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-dark);">
    <i class="fas fa-sort-numeric-down" style="color: #7C3AED;"></i> <strong>Tugas:</strong> Susun urutan langkah **Komposting Sampah Organik** dari Langkah 1 sampai Langkah 4! Gunakan panah ⬆️ ⬇️ untuk menggeser.
  </div>
</div>

<!-- Scrambled Steps Container List -->
<div id="sequence-list" style="display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 1.25rem;">
  <!-- Rendered via JS -->
</div>

<button class="btn-kid btn-kid-green" id="btn-check-sequence" style="font-size: 1.05rem;">
  <i class="fas fa-check-circle"></i> Cek Urutan Langkah!
</button>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🔢🎉</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 4 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Langkah penyelesaian masalahmu sangat tepat & berurutan!</p>

  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.2rem; font-weight: 800; color: #22C55E; margin-bottom: 1rem;">Skor: <span id="res-score">0</span></div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Ke Peta
    </a>
    <a href="level5.php" class="btn-kid btn-kid-green" style="flex: 1;">
      Lanjut Level 5 <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const stepsDB = [
    { order: 1, title: '🗑️ Pisahkan sampah organik (kulit buah/daun) dari sampah anorganik' },
    { order: 2, title: '✂️ Cacah sisa sampah organik menjadi potongan kecil' },
    { order: 3, title: '🪴 Masukkan ke wadah komposter bersama tanah & mikroorganisme' },
    { order: 4, title: '🌱 Gunakan pupuk kompos matang untuk menutrisi tanaman kebun' }
  ];

  // Scramble initial steps
  let currentList = [...stepsDB].sort(() => Math.random() - 0.5);
  let attempts = 0;

  function renderSequence() {
    const container = document.getElementById('sequence-list');
    container.innerHTML = '';

    currentList.forEach((item, index) => {
      const card = document.createElement('div');
      card.className = 'kid-card';
      card.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0.85rem; margin: 0; border-color: #7C3AED;';

      card.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.85rem; font-weight: 700;">
          <div style="width: 26px; height: 26px; background: #DDD6FE; color: #6D28D9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 900;">
            ${index + 1}
          </div>
          <div>${escapeHtml(item.title)}</div>
        </div>
        <div style="display: flex; gap: 0.3rem;">
          ${index > 0 ? `<button class="btn-move-up" data-idx="${index}" style="background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 8px; padding: 0.3rem 0.5rem; cursor: pointer;">⬆️</button>` : ''}
          ${index < currentList.length - 1 ? `<button class="btn-move-down" data-idx="${index}" style="background: #F1F5F9; border: 1px solid #CBD5E1; border-radius: 8px; padding: 0.3rem 0.5rem; cursor: pointer;">⬇️</button>` : ''}
        </div>
      `;

      container.appendChild(card);
    });

    // Move Up/Down Click handlers
    container.querySelectorAll('.btn-move-up').forEach(b => {
      b.addEventListener('click', () => {
        const idx = parseInt(b.getAttribute('data-idx'));
        swapElements(idx, idx - 1);
      });
    });

    container.querySelectorAll('.btn-move-down').forEach(b => {
      b.addEventListener('click', () => {
        const idx = parseInt(b.getAttribute('data-idx'));
        swapElements(idx, idx + 1);
      });
    });
  }

  function swapElements(idx1, idx2) {
    const temp = currentList[idx1];
    currentList[idx1] = currentList[idx2];
    currentList[idx2] = temp;
    renderSequence();
  }

  document.getElementById('btn-check-sequence').addEventListener('click', () => {
    attempts++;
    let isCorrect = true;

    for (let i = 0; i < currentList.length; i++) {
      if (currentList[i].order !== i + 1) {
        isCorrect = false;
        break;
      }
    }

    if (isCorrect) {
      ecoSound.playCorrect();
      showToast('Urutan Langkah 100% Benar!', 'success');
      finishLevel();
    } else {
      ecoSound.playWrong();
      showToast('Urutan belum pas. Periksa kembali langkah awal ke akhir!', 'warning');
    }
  });

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (attempts >= 4) stars = 1;
    else if (attempts >= 2) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = 600 - (attempts * 40);
    document.getElementById('res-score').textContent = finalScore;

    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: currentUser.key_code || 'ECO-GUEST',
        level_number: 4,
        stars_earned: stars,
        score: finalScore
      })
    });

    document.getElementById('modal-level-result').style.display = 'flex';
  }

  renderSequence();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
