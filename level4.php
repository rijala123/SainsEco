<?php
$pageTitle = "Level 4: Urutan Langkah - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Clean Top Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #7C3AED, #6D28D9); box-shadow: 0 6px 0 #5B21B6;">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🔢 Level 4: Urutan Langkah</h2>
      <p>Susun urutan penanganan sampah yang benar & rapi!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Question Card with Embedded Illustration Image -->
<div class="kid-card" style="border-color: #7C3AED;">
  <img src="assets/images/level4_bg.png" alt="Tempat Sampah Penuh Plastik dan Daun" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">
  <div style="font-size: 0.8rem; font-weight: 800; color: #6D28D9; margin-bottom: 0.35rem;">SOAL PENANGANAN SAMPAH:</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.4rem;">
    📋 Masalah: Tempat sampah penuh dengan sampah plastik dan daun 🗑️🍂
  </h3>
  <p style="font-size: 0.85rem; font-weight: 700; color: #0284C7; margin-bottom: 1rem;">
    Pilih urutan langkah yang paling tepat!
  </p>

  <div id="sequence-options-container" style="display: flex; flex-direction: column; gap: 0.65rem;">
    <button type="button" class="btn-kid seq-option-btn" data-seq="1" style="background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;">
      <span class="seq-badge" style="display: inline-block; width: 28px; height: 28px; background: #F1F5F9; color: var(--text-dark); border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;">A</span>
      Pilah → Buang → Kumpulkan
    </button>

    <button type="button" class="btn-kid seq-option-btn" data-seq="2" style="background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;">
      <span class="seq-badge" style="display: inline-block; width: 28px; height: 28px; background: #F1F5F9; color: var(--text-dark); border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;">B</span>
      Kumpulkan → Pilah → Buang
    </button>

    <button type="button" class="btn-kid seq-option-btn" data-seq="3" style="background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;">
      <span class="seq-badge" style="display: inline-block; width: 28px; height: 28px; background: #F1F5F9; color: var(--text-dark); border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;">C</span>
      Kumpulkan → Buang → Pilah
    </button>
  </div>

  <!-- Dynamic Feedback Box -->
  <div id="level4-feedback" class="feedback-box" style="display: none; margin-top: 1rem;"></div>

  <button type="button" class="btn-kid btn-kid-green" id="btn-next-level5" style="margin-top: 1rem; display: none;">
    Lanjut ke Level 5 <i class="fas fa-arrow-right"></i>
  </button>
</div>

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
  let errorsCount = 0;
  const buttons = document.querySelectorAll('.seq-option-btn');
  const feedbackEl = document.getElementById('level4-feedback');
  const btnNext = document.getElementById('btn-next-level5');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const seqVal = btn.getAttribute('data-seq');

      // Reset all buttons style & badges to default
      buttons.forEach(b => {
        b.style.cssText = 'background: #FFFFFF !important; border: 3px solid #CBD5E1 !important; color: #0F172A !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1 !important;';
        const badge = b.querySelector('.seq-badge');
        if (badge) {
          badge.style.cssText = 'display: inline-block; width: 28px; height: 28px; background: #F1F5F9 !important; color: #0F172A !important; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;';
        }
      });

      if (seqVal === '2') {
        // Correct Choice: Kumpulkan → Pilah → Buang
        ecoSound.playCorrect();
        showToast('Benar! Urutan langkah 100% tepat!', 'success');

        btn.style.cssText = 'background: linear-gradient(135deg, #22C55E, #16A34A) !important; border: 3px solid #15803D !important; color: #FFFFFF !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 5px 0 #14532D !important;';
        const badge = btn.querySelector('.seq-badge');
        if (badge) {
          badge.style.cssText = 'display: inline-block; width: 28px; height: 28px; background: #FDE047 !important; color: #15803D !important; border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;';
        }

        feedbackEl.className = 'feedback-box success';
        feedbackEl.style.display = 'block';
        feedbackEl.innerHTML = `
          <div style="font-size: 1.05rem; font-weight: 800; margin-bottom: 0.35rem;">🎉 ✨ Benar!</div>
          <div style="font-size: 0.88rem; line-height: 1.5;">
            Kumpulkan semua, pilah sesuai jenisnya, baru buang dengan benar. Lingkungan jadi rapi! 🌱
          </div>
        `;

        btnNext.style.display = 'inline-flex';
      } else {
        // Wrong Choice
        ecoSound.playWrong();
        errorsCount++;
        showToast('Urutan belum tepat! Coba lagi.', 'warning');

        btn.style.cssText = 'background: #FEE2E2 !important; border: 3px solid #EF4444 !important; color: #7F1D1D !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #991B1B !important;';

        feedbackEl.className = 'feedback-box warning';
        feedbackEl.style.display = 'block';
        feedbackEl.innerHTML = `
          <div style="font-size: 0.95rem; font-weight: 800; margin-bottom: 0.2rem;">⚠️ Urutan Belum Pas!</div>
          <div style="font-size: 0.82rem; line-height: 1.4;">
            Pikirkan alurnya: Kita harus mengumpulkan semua sampah dulu, memilah plastik & daun, baru membuangnya dengan benar.
          </div>
        `;
      }
    });
  });

  btnNext.addEventListener('click', () => {
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

    const finalScore = Math.max(100, 600 - (errorsCount * 40));
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
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
