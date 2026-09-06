<?php
$pageTitle = "Level 2: Memecahkan Masalah - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Clean Top Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #0284C7, #0369A1);">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🗂️ Level 2: Memecahkan Masalah</h2>
      <p>Analisis & temukan penyebab utama masalah lingkungan!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Step Indicator -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 800; color: #0369A1;">
  <div id="q-step-text">SOAL 1 DARI 2</div>
  <div id="q-progress-dots">🔵 ⚪</div>
</div>

<!-- Question 1 Container (Banjir) -->
<div class="kid-card" id="q1-card" style="border-color: #0284C7;">
  <img src="assets/images/level2_flood.png" alt="Banjir" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">
  <div style="font-size: 0.75rem; font-weight: 800; color: #0369A1; margin-bottom: 0.3rem;">PERMASALAHAN 1:</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.4rem;">🌊 Apa Penyebab Terjadinya Banjir Besar?</h3>
  <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.85rem;">Pilih jawaban yang tepat (Klik tombol pilihan untuk memilih, ada 4 jawaban yang benar)!</p>

  <div style="display: flex; flex-direction: column; gap: 0.6rem;" id="q1-options">
    <button type="button" class="checkbox-option-card" data-val="1">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Air tidak bisa mengalir (Pipa & selokan tersumbat)</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="2">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Air meluap karena tidak punya tempat masuk tanah</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="3">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Hujan deras tanpa penahan</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="4">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Tanah tidak menyerap air</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="wrong1">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Banyak pohon rimbun menyerap air hujan</div>
    </button>
  </div>

  <div id="q1-feedback" class="feedback-box" style="display: none;"></div>

  <button type="button" class="btn-kid btn-kid-blue" id="btn-check-q1" style="margin-top: 0.85rem;">
    <i class="fas fa-check-circle"></i> Cek Penyebab Banjir!
  </button>
  
  <button type="button" class="btn-kid btn-kid-green" id="btn-next-q2" style="margin-top: 0.85rem; display: none;">
    Lanjut ke Soal 2 <i class="fas fa-arrow-right"></i>
  </button>
</div>

<!-- Question 2 Container (Sekolah Kotor) -->
<div class="kid-card" id="q2-card" style="border-color: #D97706; display: none;">
  <img src="assets/images/level2_school.png" alt="Sekolah Kotor" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">
  <div style="font-size: 0.75rem; font-weight: 800; color: #B45309; margin-bottom: 0.3rem;">PERMASALAHAN 2:</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.4rem;">🏫 Mengapa Sekolah Jadi Kotor & Bau?</h3>
  <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.85rem;">Pilih jawaban yang tepat tentang penyebab sekolah kotor!</p>

  <div style="display: flex; flex-direction: column; gap: 0.6rem;" id="q2-options">
    <button type="button" class="checkbox-option-card" data-val="1">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Siswa bingung tempat menyimpan sampah, akhirnya berserakan di mana-mana 🤷</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="2">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Sampah tidak dikumpulkan & dibersihkan dengan baik 🧹❌</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="3">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Sisa makanan menarik lalat & kuman 🍴🦠</div>
    </button>
    <button type="button" class="checkbox-option-card" data-val="wrong1">
      <div class="checkbox-icon"></div>
      <div style="text-align: left; flex: 1;">Siswa rajin piket dan membuang sampah ke tempat sampah</div>
    </button>
  </div>

  <div id="q2-feedback" class="feedback-box" style="display: none;"></div>

  <button type="button" class="btn-kid btn-kid-yellow" id="btn-check-q2" style="margin-top: 0.85rem;">
    <i class="fas fa-check-circle"></i> Cek Penyebab Sekolah Kotor!
  </button>

  <button type="button" class="btn-kid btn-kid-green" id="btn-finish-level2" style="margin-top: 0.85rem; display: none;">
    Selesaikan Level 2 <i class="fas fa-trophy"></i>
  </button>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🗂️🎉</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 2 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Kamu berhasil menganalisis penyebab banjir & sekolah kotor dengan sempurna!</p>

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
  let errorsCount = 0;

  function toggleCard(card) {
    const isSel = card.classList.toggle('selected');
    const icon = card.querySelector('.checkbox-icon');

    if (isSel) {
      card.style.cssText = 'background: linear-gradient(135deg, #22C55E, #16A34A) !important; color: #FFFFFF !important; border-color: #15803D !important; box-shadow: 0 5px 0 #14532D !important; text-align: left; display: flex; align-items: center; gap: 0.75rem; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; font-family: var(--font-primary); font-size: 0.92rem; font-weight: 700;';
      if (icon) {
        icon.style.cssText = 'background: #FDE047 !important; border-color: #FFFFFF !important; color: #15803D !important; width: 30px; height: 30px; border-radius: 10px; border: 2.5px solid #FFFFFF; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 0 #B45309;';
        icon.innerHTML = '<i class="fas fa-check" style="color: #15803D !important; font-size: 0.95rem; font-weight: 900;"></i>';
      }
    } else {
      card.style.cssText = 'background: #FFFFFF !important; color: #0F172A !important; border-color: #CBD5E1 !important; box-shadow: 0 4px 0 #CBD5E1 !important; text-align: left; display: flex; align-items: center; gap: 0.75rem; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; font-family: var(--font-primary); font-size: 0.92rem; font-weight: 700;';
      if (icon) {
        icon.style.cssText = 'background: #F1F5F9 !important; border-color: #94A3B8 !important; color: #94A3B8 !important; width: 30px; height: 30px; border-radius: 10px; border: 2.5px solid #94A3B8; display: flex; align-items: center; justify-content: center; flex-shrink: 0;';
        icon.innerHTML = '';
      }
    }
  }

  // Question 1 Option Toggles
  const q1Cards = document.querySelectorAll('#q1-options .checkbox-option-card');
  q1Cards.forEach(card => {
    card.addEventListener('click', (e) => {
      e.preventDefault();
      toggleCard(card);
    });
  });

  // Question 2 Option Toggles
  const q2Cards = document.querySelectorAll('#q2-options .checkbox-option-card');
  q2Cards.forEach(card => {
    card.addEventListener('click', (e) => {
      e.preventDefault();
      toggleCard(card);
    });
  });

  // Check Question 1 (Banjir)
  document.getElementById('btn-check-q1').addEventListener('click', () => {
    const selectedVals = Array.from(document.querySelectorAll('#q1-options .checkbox-option-card.selected')).map(c => c.getAttribute('data-val'));
    
    // Correct choices are 1, 2, 3, 4 (4 options)
    const isExact = selectedVals.length === 4 && 
                    selectedVals.includes('1') && 
                    selectedVals.includes('2') && 
                    selectedVals.includes('3') && 
                    selectedVals.includes('4');

    const feedbackEl = document.getElementById('q1-feedback');

    if (isExact) {
      ecoSound.playCorrect();
      showToast('Sempurna! Semua penyebab banjir benar!', 'success');

      feedbackEl.className = 'feedback-box success';
      feedbackEl.style.display = 'block';
      feedbackEl.innerHTML = `
        <div style="font-size: 1.05rem; font-weight: 800; margin-bottom: 0.35rem;">🎉 Sempurna! Semua penyebab benar!</div>
        <div style="font-size: 0.85rem; line-height: 1.5;">
          💡 <strong>Dampaknya:</strong> Air tidak bisa mengalir → banjir besar! 🌊😱 Air meluap karena tidak punya tempat masuk tanah 💦 Hujan deras tanpa penahan → air membludak 💧 Tanah tidak menyerap air → air langsung mengalir 🏞️
        </div>
      `;

      document.getElementById('btn-check-q1').style.display = 'none';
      document.getElementById('btn-next-q2').style.display = 'inline-flex';
    } else {
      ecoSound.playWrong();
      errorsCount++;
      showToast('Ada pilihan yang belum pas/kurang!', 'warning');

      feedbackEl.className = 'feedback-box warning';
      feedbackEl.style.display = 'block';
      feedbackEl.innerHTML = `
        <div style="font-size: 0.95rem; font-weight: 800; margin-bottom: 0.2rem;">⚠️ Belum Tepat!</div>
        <div style="font-size: 0.82rem;">Periksa kembali! Ada 4 penyebab utama banjir yang harus dicentang semuanya.</div>
      `;
    }
  });

  // Switch to Question 2
  document.getElementById('btn-next-q2').addEventListener('click', () => {
    document.getElementById('q1-card').style.display = 'none';
    document.getElementById('q2-card').style.display = 'block';

    document.getElementById('q-step-text').textContent = 'SOAL 2 DARI 2';
    document.getElementById('q-progress-dots').textContent = '🔵 🔵';
  });

  // Check Question 2 (Sekolah Kotor)
  document.getElementById('btn-check-q2').addEventListener('click', () => {
    const selectedVals = Array.from(document.querySelectorAll('#q2-options .checkbox-option-card.selected')).map(c => c.getAttribute('data-val'));
    
    // Correct choices are 1, 2, 3
    const isExact = selectedVals.length === 3 && 
                    selectedVals.includes('1') && 
                    selectedVals.includes('2') && 
                    selectedVals.includes('3');

    const feedbackEl = document.getElementById('q2-feedback');

    if (isExact) {
      ecoSound.playCorrect();
      showToast('Sempurna! Semua penyebab sekolah kotor benar!', 'success');

      feedbackEl.className = 'feedback-box success';
      feedbackEl.style.display = 'block';
      feedbackEl.innerHTML = `
        <div style="font-size: 1.05rem; font-weight: 800; margin-bottom: 0.35rem;">🎉 Sempurna! Semua penyebab benar!</div>
        <div style="font-size: 0.85rem; line-height: 1.5;">
          💡 <strong>Dampaknya:</strong> Sampah berserakan, sekolah jadi kotor & bau 🗑️😫 Siswa bingung tempat menyimpan sampah, akhirnya berserakan di mana-mana 🤷 Sampah tidak dikumpulkan & dibersihkan dengan baik 🧹❌ Sisa makanan menarik lalat & kuman 🍴🦠
        </div>
      `;

      document.getElementById('btn-check-q2').style.display = 'none';
      document.getElementById('btn-finish-level2').style.display = 'inline-flex';
    } else {
      ecoSound.playWrong();
      errorsCount++;
      showToast('Periksa kembali centang jawabanmu!', 'warning');

      feedbackEl.className = 'feedback-box warning';
      feedbackEl.style.display = 'block';
      feedbackEl.innerHTML = `
        <div style="font-size: 0.95rem; font-weight: 800; margin-bottom: 0.2rem;">⚠️ Belum Tepat!</div>
        <div style="font-size: 0.82rem;">Pilih 3 penyebab yang membuat sekolah jadi kotor & tidak sehat!</div>
      `;
    }
  });

  // Finish Level 2
  document.getElementById('btn-finish-level2').addEventListener('click', () => {
    finishLevel();
  });

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (errorsCount >= 3) stars = 1;
    else if (errorsCount >= 1) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = Math.max(100, 500 - (errorsCount * 40));
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
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
