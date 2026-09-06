<?php
$pageTitle = "Level 1: Pengenalan Pola - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Clean Top Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #22C55E, #16A34A);">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🧩 Level 1: Pengenalan Pola</h2>
      <p>Cocokkan gambar masalah dengan penyebabnya!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Question Card with Embedded Illustration Image -->
<div class="kid-card" style="margin-bottom: 0.75rem; padding: 0.85rem;">
  <img src="assets/images/level1_bg.png" alt="Sungai Tercemar" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">
  <div style="font-size: 0.82rem; font-weight: 700; color: var(--text-dark);">
    <i class="fas fa-hand-pointer" style="color: #22C55E;"></i> <strong>Tugas:</strong> Perhatikan gambar pencemaran sungai di atas, lalu klik 1 Masalah (Kiri) & Penyebab yang cocok (Kanan)!
  </div>
</div>

<!-- Dynamic Feedback Callout Box for Level 1 -->
<div id="level1-feedback" class="feedback-box success" style="display: none; font-size: 0.85rem; font-weight: 700;">
  <!-- Rendered via JS -->
</div>

<!-- Matching Container Grid -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem;" id="matching-grid">
  <div id="col-problems" style="display: flex; flex-direction: column; gap: 0.6rem;">
    <!-- Problem Cards Rendered via JS -->
  </div>

  <div id="col-causes" style="display: flex; flex-direction: column; gap: 0.6rem;">
    <!-- Cause Cards Rendered via JS -->
  </div>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🧩🌟</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 1 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Hebat! Kamu berhasil mengenali pola masalah & penyebab lingkungan!</p>

  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.2rem; font-weight: 800; color: #22C55E; margin-bottom: 1rem;">Skor: <span id="res-score">0</span></div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Ke Peta
    </a>
    <a href="level2.php" class="btn-kid btn-kid-green" style="flex: 1;">
      Lanjut Level 2 <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const pairsDB = [
    { id: 1, prob: '🚯 Laut Tercemar Sampah Plastik', cause: '🛍️ Konsumsi Plastik Sekali Pakai Berlebihan' },
    { id: 2, prob: '😷 Polusi Asam Kabut Udara Kota', cause: '🏭 Asap Pabrik & Kendaraan Tanpa Filter' },
    { id: 3, prob: '🐟 Ikan Mati Massal di Sungai', cause: '🧪 Pembuangan Limbah B3 Kimia Beracun' },
    { id: 4, prob: '🪵 Bencana Hutan Gundul & Banjir', cause: '🪓 Penebangan Pohon Liar (Deforestasi)' }
  ];

  let selectedProb = null;
  let selectedCause = null;
  let matchesCount = 0;
  let errorsCount = 0;

  // Shuffle causes array
  const shuffledCauses = [...pairsDB].sort(() => Math.random() - 0.5);

  const colProbs = document.getElementById('col-problems');
  const colCauses = document.getElementById('col-causes');

  pairsDB.forEach(item => {
    const card = document.createElement('div');
    card.className = 'kid-card match-card';
    card.setAttribute('data-id', item.id);
    card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #38BDF8;';
    card.innerHTML = `<div style="font-size: 0.7rem; color: #0284C7; font-weight: 800; margin-bottom: 0.2rem;">MASALAH</div>${escapeHtml(item.prob)}`;
    
    card.addEventListener('click', () => {
      if (card.classList.contains('matched')) return;
      document.querySelectorAll('#col-problems .match-card').forEach(c => c.style.borderColor = '#38BDF8');
      card.style.borderColor = '#F59E0B';
      selectedProb = item.id;
      checkPair();
    });

    colProbs.appendChild(card);
  });

  shuffledCauses.forEach(item => {
    const card = document.createElement('div');
    card.className = 'kid-card match-card';
    card.setAttribute('data-id', item.id);
    card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #F59E0B;';
    card.innerHTML = `<div style="font-size: 0.7rem; color: #B45309; font-weight: 800; margin-bottom: 0.2rem;">PENYEBAB</div>${escapeHtml(item.cause)}`;

    card.addEventListener('click', () => {
      if (card.classList.contains('matched')) return;
      document.querySelectorAll('#col-causes .match-card').forEach(c => c.style.borderColor = '#F59E0B');
      card.style.borderColor = '#22C55E';
      selectedCause = item.id;
      checkPair();
    });

    colCauses.appendChild(card);
  });

  function checkPair() {
    const feedbackBox = document.getElementById('level1-feedback');

    if (selectedProb && selectedCause) {
      if (selectedProb === selectedCause) {
        // Correct match!
        ecoSound.playCorrect();
        showToast('Cocok! Pola Masalah & Penyebab Tepat!', 'success');

        if (feedbackBox) {
          feedbackBox.className = 'feedback-box success';
          feedbackBox.style.display = 'block';
          feedbackBox.innerHTML = `🎉 <strong>Sempurna!</strong> Kamu berhasil mengenali pola pasangan masalah lingkungan & penyebabnya!`;
        }

        const probEl = colProbs.querySelector(`[data-id="${selectedProb}"]`);
        const causeEl = colCauses.querySelector(`[data-id="${selectedCause}"]`);

        [probEl, causeEl].forEach(el => {
          el.classList.add('matched');
          el.style.background = '#DCFCE7';
          el.style.borderColor = '#22C55E';
          el.style.color = '#15803D';
          el.style.pointerEvents = 'none';
        });

        matchesCount++;
        selectedProb = null;
        selectedCause = null;

        if (matchesCount === pairsDB.length) {
          finishLevel();
        }
      } else {
        // Wrong match
        ecoSound.playWrong();
        errorsCount++;
        showToast('Kurang tepat! Coba pasangan yang lain.', 'warning');

        if (feedbackBox) {
          feedbackBox.className = 'feedback-box warning';
          feedbackBox.style.display = 'block';
          feedbackBox.innerHTML = `⚠️ <strong>Kurang tepat!</strong> Pasangan ini belum cocok. Amati kembali penyebab utama dari masalah ini!`;
        }
        
        selectedProb = null;
        selectedCause = null;
        setTimeout(() => {
          document.querySelectorAll('#col-problems .match-card:not(.matched)').forEach(c => c.style.borderColor = '#38BDF8');
          document.querySelectorAll('#col-causes .match-card:not(.matched)').forEach(c => c.style.borderColor = '#F59E0B');
        }, 500);
      }
    }
  }

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (errorsCount >= 3) stars = 1;
    else if (errorsCount >= 1) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = 500 - (errorsCount * 50);
    document.getElementById('res-score').textContent = finalScore;

    // Save level result to API
    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: currentUser.key_code || 'ECO-GUEST',
        level_number: 1,
        stars_earned: stars,
        score: finalScore
      })
    });

    document.getElementById('modal-level-result').style.display = 'flex';
  }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
