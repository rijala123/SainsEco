<?php
$pageTitle = "Level 5: Tindakan Nyata - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #EC4899, #DB2777); box-shadow: 0 8px 0 #9D174D;">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🌟 Level 5: Decision Game</h2>
      <p>Simulasi tindakan nyata penyelamat bumi!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Scenario Decision Box -->
<div class="kid-card" id="decision-card">
  <div style="font-size: 0.72rem; font-weight: 800; color: #DB2777; margin-bottom: 0.3rem;" id="decision-step">SKENARIO 1 DARI 3</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.5rem;" id="decision-title">Memuat...</h3>
  <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1rem;" id="decision-desc">Memuat...</p>

  <div style="font-weight: 800; font-size: 0.85rem; color: #15803D; margin-bottom: 0.6rem;">
    🌱 Pilih Tindakan Nyata Yang Paling Pikirkan Bumi:
  </div>

  <div id="choices-container" style="display: flex; flex-direction: column; gap: 0.6rem;">
    <!-- Rendered via JS -->
  </div>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 4rem; margin-bottom: 0.2rem;">🏆🌟👑</div>
  <h2 style="font-size: 1.6rem; color: var(--text-dark); margin-bottom: 0.2rem;">PETUALANGAN SELESAI!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Kamu telah berhasil menyelesaikan 5 Level Peta Petualangan Eco!</p>
  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.2rem; font-weight: 800; color: #22C55E; margin-bottom: 0.5rem;">Skor: <span id="res-score">0</span></div>
  <div style="font-size: 0.95rem; font-weight: 900; color: #F59E0B; margin-bottom: 1.25rem;">Lencana: Grand Eco Champion 🥇</div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Peta Petualangan
    </a>
    <a href="klasemen.php" class="btn-kid btn-kid-yellow" style="flex: 1;">
      <i class="fas fa-trophy"></i> Lihat Klasemen
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const decisionsDB = [
    {
      title: '🛒 Skenario 1: Belanja di Minimarket / Pasar',
      desc: 'Saat kamu diajak berbelanja keperluan sekolah, kasir menawarkan kantong plastik sekali pakai. Apa tindakan nyatamu?',
      choices: [
        { text: '👜 Mengeluarkan kantong kain ramah lingkungan sendiri dari tas', points: 200, isBest: true, msg: 'Hebat! Kamu menghemat 1 sampah plastik dari laut!' },
        { text: '🛍️ Menerima 3 kantong plastik sekali pakai gratis', points: -50, isBest: false, msg: 'Kantong plastik butuh 500 tahun terurai di alam.' }
      ]
    },
    {
      title: '🏫 Skenario 2: Minum Saat Jam Istirahat Sekolah',
      desc: 'Kamu merasa haus setelah berolahraga di lapangan sekolah. Tindakan paling tepat yang kamu lakukan adalah...',
      choices: [
        { text: '🧴 Minum dari Tumbler botol minum isi ulang sendiri', points: 200, isBest: true, msg: 'Pilihan bijak! Kamu menghemat uang jajan & mengurangi sampah botol.' },
        { text: '🥤 Membeli 2 botol air kemasan plastik sekali pakai', points: -50, isBest: false, msg: 'Botol plastik bekas menumpuk di tempat sampah sekolah.' }
      ]
    },
    {
      title: '🍂 Skenario 3: Memeriksakan Sampah Daun di Halaman',
      desc: 'Halaman sekolah penuh dengan guguran daun kering. Apa tindakan nyata terbaik?',
      choices: [
        { text: '🌱 Kumpulkan daun dan masukkan ke komposter untuk dijadikan pupuk', points: 200, isBest: true, msg: 'Luar biasa! Pupuk kompos menutrisi kebun sekolah!' },
        { text: '💨 Membakar tumpukan daun hingga berasap tebal', points: -100, isBest: false, msg: 'Asap pembakaran daun mencemari udara & mengganggu pernapasan.' }
      ]
    }
  ];

  let currentIndex = 0;
  let totalScore = 0;
  let bestChoicesCount = 0;

  function renderDecision() {
    if (currentIndex >= decisionsDB.length) {
      finishLevel();
      return;
    }

    const dec = decisionsDB[currentIndex];
    document.getElementById('decision-step').textContent = `SKENARIO ${currentIndex + 1} DARI ${decisionsDB.length}`;
    document.getElementById('decision-title').textContent = dec.title;
    document.getElementById('decision-desc').textContent = dec.desc;

    const choicesContainer = document.getElementById('choices-container');
    choicesContainer.innerHTML = '';

    dec.choices.forEach(ch => {
      const btn = document.createElement('button');
      btn.className = 'btn-kid';
      btn.style.cssText = 'background: #FFFFFF; border: 2px solid #CBD5E1; color: var(--text-dark); font-size: 0.85rem; text-align: left; padding: 0.85rem 1rem; border-radius: 16px; width: 100%;';
      btn.innerHTML = `${escapeHtml(ch.text)}`;

      btn.addEventListener('click', () => {
        totalScore += ch.points;
        if (ch.isBest) {
          bestChoicesCount++;
          ecoSound.playCorrect();
          showToast(ch.msg, 'success');
        } else {
          ecoSound.playWrong();
          showToast(ch.msg, 'warning');
        }

        currentIndex++;
        renderDecision();
      });

      choicesContainer.appendChild(btn);
    });
  }

  function finishLevel() {
    ecoSound.playLevelUp();

    let stars = 3;
    if (bestChoicesCount < 2) stars = 1;
    else if (bestChoicesCount < 3) stars = 2;

    let starStr = '';
    for (let s = 1; s <= 3; s++) starStr += (s <= stars) ? '⭐' : '☆';
    document.getElementById('res-stars').textContent = starStr;

    const finalScore = Math.max(100, totalScore);
    document.getElementById('res-score').textContent = finalScore;

    // Save level result and grant highest score
    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: currentUser.key_code || 'ECO-GUEST',
        level_number: 5,
        stars_earned: stars,
        score: finalScore
      })
    });

    document.getElementById('modal-level-result').style.display = 'flex';
  }

  renderDecision();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
