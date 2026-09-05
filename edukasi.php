<?php
$pageTitle = "Materi Edukasi - Eco Clean App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Banner Header -->
<div class="kid-banner" style="background: linear-gradient(135deg, #0284C7, #0369A1); box-shadow: 0 8px 0 #075985;">
  <div style="display: flex; align-items: center; gap: 0.75rem;">
    <div style="font-size: 2.5rem;">📚🌱</div>
    <div>
      <h2>Edukasi Eco Sains!</h2>
      <p>Belajar pilah sampah & jawab kuis serunya!</p>
    </div>
  </div>
</div>

<!-- Category Chips Horizontal Scroll -->
<div style="display: flex; gap: 0.5rem; overflow-x: auto; padding-bottom: 0.5rem; margin-bottom: 1rem;">
  <button class="btn-kid category-filter-btn" data-cat="all" style="padding: 0.4rem 0.9rem; font-size: 0.82rem; background: #22C55E; color: #FFF; width: auto; white-space: nowrap;">
    Semua
  </button>
  <button class="btn-kid category-filter-btn" data-cat="organik" style="padding: 0.4rem 0.9rem; font-size: 0.82rem; background: #FFFFFF; color: #15803D; border: 2px solid #22C55E; width: auto; white-space: nowrap;">
    🍃 Organik
  </button>
  <button class="btn-kid category-filter-btn" data-cat="anorganik" style="padding: 0.4rem 0.9rem; font-size: 0.82rem; background: #FFFFFF; color: #B45309; border: 2px solid #F59E0B; width: auto; white-space: nowrap;">
    ♻️ Anorganik
  </button>
  <button class="btn-kid category-filter-btn" data-cat="b3" style="padding: 0.4rem 0.9rem; font-size: 0.82rem; background: #FFFFFF; color: #B91C1C; border: 2px solid #EF4444; width: auto; white-space: nowrap;">
    ☣️ B3
  </button>
</div>

<!-- Educational Cards List -->
<div id="edu-materials-container">
  <!-- Rendered via JS -->
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  let materialsData = [];

  function fetchMaterials() {
    fetch('api/index.php?action=get_materials')
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          materialsData = res.data;
          renderMaterials('all');
        }
      });
  }

  function renderMaterials(category = 'all') {
    const container = document.getElementById('edu-materials-container');
    if (!container) return;

    const filtered = (category === 'all') ? materialsData : materialsData.filter(i => i.category === category);

    if (filtered.length === 0) {
      container.innerHTML = '<div class="kid-card" style="text-align: center; color: var(--text-muted);">Tidak ada materi dalam kategori ini.</div>';
      return;
    }

    let html = '';
    filtered.forEach(item => {
      let badgeTag = '🍃 ORGANIK';
      let tagBg = '#DCFCE7';
      let tagColor = '#15803D';

      if (item.category === 'anorganik') { badgeTag = '♻️ ANORGANIK'; tagBg = '#FEF3C7'; tagColor = '#B45309'; }
      if (item.category === 'b3') { badgeTag = '☣️ SAMPAL B3'; tagBg = '#FEE2E2'; tagColor = '#B91C1C'; }
      if (item.category === 'prinsip_3r') { badgeTag = '🌍 PRINSIP 3R'; tagBg = '#E0F2FE'; tagColor = '#0369A1'; }

      let optionsHtml = '';
      if (item.quiz_options && Array.isArray(item.quiz_options)) {
        item.quiz_options.forEach((opt, idx) => {
          optionsHtml += `
            <button class="quiz-option-btn" onclick="submitQuiz(${item.id}, ${idx}, this)" style="display: block; width: 100%; text-align: left; padding: 0.6rem 0.85rem; margin-bottom: 0.4rem; background: #FFFFFF; border: 2px solid #CBD5E1; border-radius: 12px; font-family: var(--font-primary); font-size: 0.85rem; cursor: pointer;">
              ${String.fromCharCode(65 + idx)}. ${escapeHtml(opt)}
            </button>
          `;
        });
      }

      html += `
        <div class="kid-card">
          <span style="display: inline-block; padding: 0.25rem 0.65rem; background: ${tagBg}; color: ${tagColor}; font-weight: 800; font-size: 0.72rem; border-radius: 20px; margin-bottom: 0.6rem;">
            ${badgeTag}
          </span>
          <h3 style="font-size: 1.15rem; color: var(--text-dark); margin-bottom: 0.4rem;">${escapeHtml(item.title)}</h3>
          <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">${escapeHtml(item.short_desc)}</p>

          <div style="background: #F8FAFC; padding: 0.75rem; border-radius: 14px; border-left: 3px solid #22C55E; margin-bottom: 0.85rem;">
            <p style="font-size: 0.82rem; line-height: 1.5; color: var(--text-dark);">${escapeHtml(item.content)}</p>
            <div style="margin-top: 0.4rem; font-size: 0.78rem; color: #15803D; font-weight: 700;">
              💡 Contoh: ${escapeHtml(item.examples)}
            </div>
          </div>

          <div style="border-top: 2px dashed #E2E8F0; padding-top: 0.75rem;">
            <div style="font-weight: 800; font-size: 0.85rem; color: #D97706; margin-bottom: 0.4rem;">
              <i class="fas fa-question-circle"></i> Kuis Sains:
            </div>
            <div style="font-size: 0.82rem; font-weight: 700; margin-bottom: 0.5rem;">${escapeHtml(item.quiz_question)}</div>
            <div id="quiz-options-${item.id}">${optionsHtml}</div>
            <div id="quiz-result-${item.id}" style="margin-top: 0.4rem;"></div>
          </div>
        </div>
      `;
    });

    container.innerHTML = html;
  }

  const filterBtns = document.querySelectorAll('.category-filter-btn');
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      renderMaterials(btn.getAttribute('data-cat'));
    });
  });

  window.submitQuiz = function(materialId, selectedIndex, btnEl) {
    const parentContainer = document.getElementById(`quiz-options-${materialId}`);
    const resultBox = document.getElementById(`quiz-result-${materialId}`);

    fetch('api/index.php?action=submit_quiz', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ material_id: materialId, answer_index: selectedIndex })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        const allBtns = parentContainer.querySelectorAll('.quiz-option-btn');
        allBtns.forEach((b, idx) => {
          b.disabled = true;
          if (idx === res.correct_index) {
            b.style.background = '#DCFCE7';
            b.style.borderColor = '#22C55E';
            b.style.color = '#15803D';
          } else if (idx === selectedIndex && !res.is_correct) {
            b.style.background = '#FEE2E2';
            b.style.borderColor = '#EF4444';
          }
        });

        if (res.is_correct) {
          ecoSound.playCorrect();
          resultBox.innerHTML = `<div style="color: #15803D; font-size: 0.8rem; font-weight: 800;"><i class="fas fa-check"></i> ${res.message}</div>`;
        } else {
          ecoSound.playWrong();
          resultBox.innerHTML = `<div style="color: #B91C1C; font-size: 0.8rem; font-weight: 800;"><i class="fas fa-times"></i> ${res.message}</div>`;
        }
      }
    });
  };

  fetchMaterials();
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
