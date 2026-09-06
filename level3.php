<?php
$pageTitle = "Level 3: Filter Solusi - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Clean Top Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #F59E0B, #D97706); box-shadow: 0 6px 0 #B45309;">
  <div style="display: flex; align-items: center; justify-content: space-between;">
    <div>
      <h2>🔍 Level 3: Filter Solusi Inti</h2>
      <p>Atasi penumpukan sampah di sekolah dengan memilih solusi terbaik!</p>
    </div>
    <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
      <i class="fas fa-map"></i> Peta
    </a>
  </div>
</div>

<!-- Scenario Filter Card with Embedded Illustration Image -->
<div class="kid-card" id="scenario-card" style="border-color: #F59E0B;">
  <img src="assets/images/level3_bg.png" alt="Penumpukan Sampah di Sekolah" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">
  <div style="font-size: 0.72rem; font-weight: 800; color: #B45309; margin-bottom: 0.3rem;" id="scenario-step">SKENARIO 1 DARI 3</div>
  <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.6rem;" id="scenario-title">Memuat Skenario...</h3>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;" id="scenario-desc">Memuat Deskripsi...</p>

  <div style="font-weight: 800; font-size: 0.85rem; color: #0284C7; margin-bottom: 0.5rem;">
    💡 Filter & Pilih Solusi Paling Utama & Penting:
  </div>

  <div id="options-container" style="display: flex; flex-direction: column; gap: 0.5rem;">
    <!-- Rendered via JS -->
  </div>

  <div id="level3-feedback" class="feedback-box" style="display: none; margin-top: 0.85rem;"></div>
</div>

<!-- Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.96); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;">🔍🎉</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;">Level 3 Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Luar biasa! Kamu berhasil menyaring solusi paling penting untuk penumpukan sampah sekolah!</p>

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
      title: '🏫 Skenario 1: Penumpukan Sampah Plastik & Makanan di Kantin Sekolah',
      desc: 'Setiap jam istirahat, sampah plastik bekas bungkus jajan dan sisa makanan menumpuk tinggi di tempat sampah sekolah sampai meluber.',
      options: [
        { text: '🌱 Sediakan tempat sampah pilah (Organik & Plastik) & galakkan gerakan bawa tempat makan/tumbler sendiri', correct: true, feedback: '🎉 Tepat Sekali! Mengurangi dari sumbernya & memilah sampah adalah solusi paling penting & berdampak panjang!' },
        { text: '🔥 Membakar seluruh tumpukan sampah plastik di halaman sekolah setiap sore', correct: false, feedback: '⚠️ Salah! Membakar sampah menghasilkan asap beracun (dioksin) yang berbahaya bagi pernapasan anak sekolah!' },
        { text: '🗑️ Membiarkan sampah menumpuk dan menunggu tertiup angin', correct: false, feedback: '⚠️ Kurang tepat! Membiarkan sampah akan mengundang lalat, kecoa, dan menimbulkan bau menyengat!' }
      ]
    },
    {
      title: '🍂 Skenario 2: Penumpukan Daun Kering di Kebun Sekolah',
      desc: 'Pohon-pohon di sekolah meluruhkan banyak daun kering hingga menumpuk tebal di halaman.',
      options: [
        { text: '💨 Membuang seluruh daun kering ke dalam selokan saluran air', correct: false, feedback: '⚠️ Salah! Membuang daun ke selokan akan menyumbat saluran air dan menyebabkan banjir!' },
        { text: '🪴 Olah daun kering menjadi pupuk komposting organik untuk tanaman sekolah', correct: true, feedback: '🎉 Sempurna! Daun kering adalah bahan organik terbaik untuk nutrisi tanah kebun sekolah!' },
        { text: '🛍️ Membungkus daun kering dengan 100 kantong plastik lalu dibuang begitu saja', correct: false, feedback: '⚠️ Kurang tepat! Menggunakan banyak kantong plastik justru menambah pencemaran sampah plastik!' }
      ]
    },
    {
      title: '📦 Skenario 3: Penumpukan Kardus & Kertas Bekas di Ruang Kelas',
      desc: 'Banyak kardus dan kertas tugas lama yang menumpuk tak terpakai di belakang kelas.',
      options: [
        { text: '♻️ Kumpulkan kertas & kardus untuk disalurkan ke bank sampah / tempat daur ulang', correct: true, feedback: '🎉 Luar Biasa! Kertas dan kardus dapat didaur ulang 100% menjadi barang berguna baru!' },
        { text: '🌊 Membuang kertas ke dalam toilet atau sungai dekat sekolah', correct: false, feedback: '⚠️ Salah! Membuang kertas ke toilet menyumbat saluran pipa air!' }
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
    const feedbackBox = document.getElementById('level3-feedback');
    feedbackBox.style.display = 'none';
    optContainer.innerHTML = '';

    sc.options.forEach((opt, idx) => {
      const btn = document.createElement('button');
      btn.className = 'btn-kid';
      btn.style.cssText = 'background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.85rem; text-align: left; padding: 0.85rem 1rem; border-radius: 16px; width: 100%;';
      btn.innerHTML = `<strong>${String.fromCharCode(65 + idx)}.</strong> ${escapeHtml(opt.text)}`;

      btn.addEventListener('click', () => {
        if (opt.correct) {
          ecoSound.playCorrect();
          showToast('Solusi utama tepat!', 'success');
          
          feedbackBox.className = 'feedback-box success';
          feedbackBox.style.display = 'block';
          feedbackBox.innerHTML = opt.feedback;

          setTimeout(() => {
            currentIndex++;
            renderScenario();
          }, 1800);
        } else {
          ecoSound.playWrong();
          errorsCount++;
          showToast('Pilihan kurang tepat!', 'warning');

          feedbackBox.className = 'feedback-box warning';
          feedbackBox.style.display = 'block';
          feedbackBox.innerHTML = opt.feedback;
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

    const finalScore = Math.max(100, 600 - (errorsCount * 50));
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
