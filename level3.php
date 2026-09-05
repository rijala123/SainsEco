<?php
$pageTitle = "Level 3: Filter Solusi - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #F59E0B, #D97706); box-shadow: 0 8px 0 #B45309;">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🔍 Level 3: Filter Solusi Inti</h2>
      <p>Pilih solusi yang paling penting & berdampak!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<div class="kid-card" id="scenario-card">
  <div style="font-size: 0.72rem; font-weight: 800; color: #B45309; margin-bottom: 0.3rem;" id="scenario-step">SKENARIO 1 DARI 3</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.6rem;" id="scenario-title">Memuat Skenario...</h3>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;" id="scenario-desc">Memuat Deskripsi...</p>

  <div style="font-weight: 800; font-size: 0.85rem; color: #0284C7; margin-bottom: 0.5rem;">
    💡 Filter & Pilih Solusi Paling Utama:
  </div>
  <div id="options-container" style="display: flex; flex-direction: column; gap: 0.5rem;">
    <!-- Rendered via JS -->
  </div>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🔍🎉</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 3 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Luar biasa! Kamu berhasil menyaring solusi paling penting!</p>

  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.2rem; font-weight: 800; color: #22C55E; margin-bottom: 1rem;">Skor: <span id="res-score">0</span></div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Ke Peta
    </a>
    <a href="level4.php" class="btn-kid btn-kid-green" style="flex: 1;">
      Lanjut Level 4 <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const scenariosDB = [
    {
      title: '🚨 Laut Tercemar Sampah Plastik Sekali Pakai',
      desc: 'Penyu dan hewan laut tidak sengaja memakan kantong plastik. Apa solusi paling utama untuk menghentikan masalah ini di akarnya?',
      options: [
        { text: 'Kurangi penggunaan plastik sekali pakai & gunakan tumbler/tas belanja kain', correct: true },
        { text: 'Membakar sampah plastik di tepi pantai', correct: false },
        { text: 'Mengubur sampah plastik di dasar laut', correct: false }
      ]
    },
    {
      title: '🚨 Udara Kota Tercemar Asap Kendaraan',
      desc: 'Polusi asap kendaraan membuat banyak anak terkena batuk ISPA. Solusi paling efektif adalah...',
      options: [
        { text: 'Menutup seluruh jendela dan tidak keluar rumah selamanya', correct: false },
        { text: 'Gunakan transportasi umum/sepeda & perbanyak pohon penghijauan kota', correct: true },
        { text: 'Menyemprotkan parfum ke asap knalpot', correct: false }
      ]
    },
    {
      title: '🚨 Bahaya Baterai Bekas Tercampur Sampah Makanan',
      desc: 'Baterai bekas mengandung racun logam berat yang dapat meracuni air tanah jika dibuang sembarangan.',
      options: [
        { text: 'Membuang baterai ke dalam kolam atau sungai', correct: false },
        { text: 'Membakar baterai bersama sampah daun', correct: false },
        { text: 'Pisahkan baterai bekas dan salurkan ke tempat penampungan limbah B3', correct: true }
      ]
    }
  ];

  let currentIndex = 0;
  let errorsCount = 0;

  function renderScenario() {
    if (currentIndex >= scenariosDB.length) {
      finishLevel();
      return;
    }

    const sc = scenariosDB[currentIndex];
    document.getElementById('scenario-step').textContent = `SKENARIO ${currentIndex + 1} DARI ${scenariosDB.length}`;
    document.getElementById('scenario-title').textContent = sc.title;
    document.getElementById('scenario-desc').textContent = sc.desc;

    const optContainer = document.getElementById('options-container');
    optContainer.innerHTML = '';

    sc.options.forEach((opt, idx) => {
      const btn = document.createElement('button');
      btn.className = 'btn-kid';
      btn.style.cssText = 'background: #FFFFFF; border: 2px solid #CBD5E1; color: var(--text-dark); font-size: 0.85rem; text-align: left; padding: 0.75rem 1rem; border-radius: 16px; width: 100%;';
      btn.innerHTML = `${String.fromCharCode(65 + idx)}. ${escapeHtml(opt.text)}`;

      btn.addEventListener('click', () => {
        if (opt.correct) {
          ecoSound.playCorrect();
          showToast('Tepat! Solusi paling berdampak telah dipilih!', 'success');
          currentIndex++;
          renderScenario();
        } else {
          ecoSound.playWrong();
          errorsCount++;
          showToast('Pilihan ini kurang efektif. Coba filter solusi utama lainnya!', 'warning');
        }
      });

      optContainer.appendChild(btn);
    });
  }

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (errorsCount >= 3) stars = 1;
    else if (errorsCount >= 1) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = 600 - (errorsCount * 50);
    document.getElementById('res-score').textContent = finalScore;

    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: currentUser.key_code || 'ECO-GUEST',
        level_number: 3,
        stars_earned: stars,
        score: finalScore
      })
    });

    document.getElementById('modal-level-result').style.display = 'flex';
  }

  renderScenario();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
