<?php
$levelId = intval($_GET['id'] ?? $_GET['level_number'] ?? 1);
$pageTitle = "Level $levelId - Eco Clean Mobile App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Dynamic Game Container -->
<div id="dynamic-level-app" data-level="<?= $levelId ?>">
  <div style="text-align: center; padding: 3rem 1rem;">
    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;" class="animate-bounce">⏳</div>
    <h3 style="color: var(--text-dark); font-weight: 800;">Memuat Level <?= $levelId ?>...</h3>
    <p style="color: var(--text-muted); font-size: 0.85rem;">Menyiapkan petualangan sains lingkungan...</p>
  </div>
</div>

<!-- Dynamic Modal Level Result -->
<div id="modal-level-result" class="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.97); z-index: 50; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.2rem;" id="res-emoji">🎉🌟</div>
  <h2 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.2rem;" id="res-title">Level Selesai!</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;" id="res-subtitle">Hebat! Kamu berhasil menyelesaikan tantangan level ini!</p>

  <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" id="res-stars">⭐⭐⭐</div>
  <div style="font-size: 1.25rem; font-weight: 900; color: #22C55E; margin-bottom: 1.25rem;">Skor: <span id="res-score">0</span></div>

  <div style="display: flex; gap: 0.5rem; width: 100%;">
    <a href="map.php" class="btn-kid btn-kid-blue" style="flex: 1;">
      <i class="fas fa-map"></i> Ke Peta
    </a>
    <a id="btn-next-level" href="map.php" class="btn-kid btn-kid-green" style="flex: 1;">
      Lanjut Level <i class="fas fa-arrow-right"></i>
    </a>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const levelId = parseInt(document.getElementById('dynamic-level-app').getAttribute('data-level')) || 1;
  const userKey = currentUser.key_code || 'ECO-GUEST';

  fetch(`api/index.php?action=get_level_detail&level_number=${levelId}`)
    .then(res => res.json())
    .then(res => {
      if (res.success && res.data) {
        renderLevelPage(res.data);
      } else {
        document.getElementById('dynamic-level-app').innerHTML = `
          <div class="kid-card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">⚠️</div>
            <h3>Level ${levelId} Belum Tersedia</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">Guru belum menambahkan konten untuk level ini.</p>
            <a href="map.php" class="btn-kid btn-kid-blue">Kembali ke Peta</a>
          </div>
        `;
      }
    })
    .catch(err => {
      console.error(err);
      showToast('Gagal memuat level. Membuka mode offline...', 'warning');
    });

  function renderLevelPage(lvl) {
    const app = document.getElementById('dynamic-level-app');
    const content = lvl.content || {};
    const bgColor = lvl.bg_color || '#22C55E';
    const icon = lvl.icon || '🧩';

    let html = `
      <!-- Top Banner Header -->
      <div class="kid-banner" style="background: linear-gradient(135deg, ${bgColor}, ${adjustColor(bgColor, -20)}); box-shadow: 0 6px 0 ${adjustColor(bgColor, -40)};">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <div>
            <h2>${icon} ${escapeHtml(lvl.title)}</h2>
            <p>${escapeHtml(lvl.subtitle || '')}</p>
          </div>
          <a href="map.php" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.25); color: #FFF; font-size: 0.8rem;">
            <i class="fas fa-map"></i> Peta
          </a>
        </div>
      </div>
    `;

    // Dynamic Game Type Renderer
    if (lvl.game_type === 'matching') {
      html += renderMatchingGame(lvl, content);
    } else if (lvl.game_type === 'multichoice') {
      html += renderMultichoiceGame(lvl, content);
    } else if (lvl.game_type === 'filter') {
      html += renderFilterGame(lvl, content);
    } else if (lvl.game_type === 'sequence') {
      html += renderSequenceGame(lvl, content);
    } else if (lvl.game_type === 'decision') {
      html += renderDecisionGame(lvl, content);
    } else {
      html += `<div class="kid-card"><p>Tipe game "${lvl.game_type}" tidak dikenali.</p></div>`;
    }

    app.innerHTML = html;

    // Attach Interactive Event Handlers per Game Type
    if (lvl.game_type === 'matching') initMatchingLogic(lvl, content);
    else if (lvl.game_type === 'multichoice') initMultichoiceLogic(lvl, content);
    else if (lvl.game_type === 'filter') initFilterLogic(lvl, content);
    else if (lvl.game_type === 'sequence') initSequenceLogic(lvl, content);
    else if (lvl.game_type === 'decision') initDecisionLogic(lvl, content);
  }

  // Color Helper function
  function adjustColor(hex, percent) {
    if (!hex || hex[0] !== '#') return hex || '#22C55E';
    let num = parseInt(hex.slice(1), 16),
        amt = Math.round(2.55 * percent),
        R = (num >> 16) + amt,
        G = (num >> 8 & 0x00FF) + amt,
        B = (num & 0x0000FF) + amt;
    return "#" + (0x1000000 + (R<255?R<1?0:R:255)*0x10000 + (G<255?G<1?0:G:255)*0x100 + (B<255?B<1?0:B:255)).toString(16).slice(1);
  }

  // 1. MATCHING GAME RENDERER & LOGIC
  function renderMatchingGame(lvl, content) {
    const pairs = content.pairs || [];
    return `
      ${lvl.illustration ? `<div class="kid-card" style="margin-bottom: 0.75rem; padding: 0.85rem;"><img src="${escapeHtml(lvl.illustration)}" alt="Ilustrasi" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;"><div style="font-size: 0.82rem; font-weight: 700; color: var(--text-dark);"><i class="fas fa-hand-pointer" style="color: #22C55E;"></i> ${escapeHtml(lvl.instructions || 'Klik 1 Masalah (Kiri) & 1 Penyebab (Kanan) yang cocok!')}</div></div>` : ''}
      
      <div style="font-weight: 800; font-size: 0.88rem; color: #0369A1; margin-bottom: 0.6rem; text-align: center; background: #E0F2FE; padding: 0.45rem 0.8rem; border-radius: 14px; border: 2px solid #BAE6FD;">
        🧩 Pasangan Cocok: <span id="match-count-num" style="color: #16A34A; font-size: 1.05rem; font-weight: 900;">0</span> / ${pairs.length} Pasangan
      </div>

      <div id="matching-feedback" class="feedback-box success" style="display: none; font-size: 0.85rem; font-weight: 700;"></div>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; padding-bottom: 3rem;" id="matching-grid">
        <div id="col-problems" style="display: flex; flex-direction: column; gap: 0.6rem;"></div>
        <div id="col-causes" style="display: flex; flex-direction: column; gap: 0.6rem;"></div>
      </div>

      <button type="button" class="btn-kid btn-kid-green" id="btn-matching-finish-direct" style="margin-top: 0.85rem; padding: 0.9rem; font-size: 1rem; display: none;">
        Lanjut Ke Level ${parseInt(lvl.level_number) + 1} <i class="fas fa-arrow-right"></i>
      </button>
    `;
  }

  function initMatchingLogic(lvl, content) {
    const pairsDB = content.pairs || [];
    let selectedProb = null, selectedCause = null, matchesCount = 0, errorsCount = 0;
    const shuffledCauses = [...pairsDB].sort(() => Math.random() - 0.5);

    const colProbs = document.getElementById('col-problems');
    const colCauses = document.getElementById('col-causes');

    function resetProblemCardsStyle() {
      document.querySelectorAll('#col-problems .match-card:not(.matched)').forEach(c => {
        c.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #38BDF8; background: #FFFFFF; color: #0F172A; box-shadow: 0 4px 0 #CBD5E1;';
      });
    }

    function resetCauseCardsStyle() {
      document.querySelectorAll('#col-causes .match-card:not(.matched)').forEach(c => {
        c.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #A855F7; background: #FFFFFF; color: #0F172A; box-shadow: 0 4px 0 #CBD5E1;';
      });
    }

    pairsDB.forEach(item => {
      const card = document.createElement('div');
      card.className = 'kid-card match-card';
      card.setAttribute('data-id', String(item.id));
      card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #38BDF8;';
      card.innerHTML = `<div style="font-size: 0.7rem; color: #0284C7; font-weight: 800; margin-bottom: 0.2rem;">MASALAH</div>${escapeHtml(item.prob)}`;
      
      card.addEventListener('click', () => {
        if (card.classList.contains('matched')) return;
        resetProblemCardsStyle();
        card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 800; background: #FEF3C7 !important; border: 2.5px solid #F59E0B !important; color: #B45309 !important; box-shadow: 0 4px 0 #F59E0B !important;';
        selectedProb = String(item.id);
        checkPair();
      });
      colProbs.appendChild(card);
    });

    shuffledCauses.forEach(item => {
      const card = document.createElement('div');
      card.className = 'kid-card match-card';
      card.setAttribute('data-id', String(item.id));
      card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 700; border-color: #A855F7;';
      card.innerHTML = `<div style="font-size: 0.7rem; color: #7E22CE; font-weight: 800; margin-bottom: 0.2rem;">PENYEBAB</div>${escapeHtml(item.cause)}`;
      
      card.addEventListener('click', () => {
        if (card.classList.contains('matched')) return;
        resetCauseCardsStyle();
        card.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; cursor: pointer; text-align: center; font-size: 0.8rem; font-weight: 800; background: #FEF3C7 !important; border: 2.5px solid #F59E0B !important; color: #B45309 !important; box-shadow: 0 4px 0 #F59E0B !important;';
        selectedCause = String(item.id);
        checkPair();
      });
      colCauses.appendChild(card);
    });

    function checkPair() {
      if (!selectedProb || !selectedCause) return;

      const pCard = document.querySelector(`#col-problems .match-card[data-id="${selectedProb}"]`);
      const cCard = document.querySelector(`#col-causes .match-card[data-id="${selectedCause}"]`);

      if (selectedProb === selectedCause) {
        ecoSound.playCorrect();
        showToast('Pasangan Cocok! 🎉', 'success');
        matchesCount++;

        const countNumEl = document.getElementById('match-count-num');
        if (countNumEl) countNumEl.textContent = matchesCount;

        if (pCard && cCard) {
          [pCard, cCard].forEach(c => {
            c.classList.add('matched');
            c.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; background: #DCFCE7 !important; border: 2.5px solid #22C55E !important; color: #15803D !important; font-weight: 800; font-size: 0.8rem; box-shadow: 0 4px 0 #15803D !important;';
          });
        }
        selectedProb = null; selectedCause = null;

        if (matchesCount >= pairsDB.length) {
          const btnDirect = document.getElementById('btn-matching-finish-direct');
          if (btnDirect) {
            btnDirect.style.display = 'block';
            btnDirect.onclick = () => {
              window.location.href = `level.php?id=${parseInt(lvl.level_number) + 1}`;
            };
          }
          setTimeout(() => {
            finishLevel(lvl.level_number, errorsCount);
          }, 600);
        }
      } else {
        ecoSound.playWrong();
        showToast('Belum cocok! Coba pasangan yang lain.', 'error');
        errorsCount++;

        if (pCard && cCard) {
          [pCard, cCard].forEach(c => {
            c.style.cssText = 'padding: 0.75rem 0.5rem; margin: 0; background: #FEE2E2 !important; border: 2.5px solid #EF4444 !important; color: #991B1B !important; font-weight: 800; font-size: 0.8rem; box-shadow: 0 4px 0 #B91C1C !important;';
          });
        }

        selectedProb = null; selectedCause = null;
        setTimeout(() => {
          resetProblemCardsStyle();
          resetCauseCardsStyle();
        }, 650);
      }
    }
  }

  // 2. MULTICHOICE GAME RENDERER & LOGIC
  function renderMultichoiceGame(lvl, content) {
    const questions = content.questions || [];
    return `
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 800; color: #0369A1;">
        <div id="q-step-text">SOAL 1 DARI ${questions.length}</div>
        <div id="q-progress-dots">${'🔵 '.repeat(1)}${'⚪ '.repeat(Math.max(0, questions.length - 1))}</div>
      </div>
      <div id="mc-questions-wrapper">
        ${questions.map((q, idx) => `
          <div class="kid-card mc-q-card" id="mc-q-card-${idx}" style="border-color: ${lvl.bg_color}; display: ${idx === 0 ? 'block' : 'none'};">
            ${q.illustration ? `<img src="${escapeHtml(q.illustration)}" alt="Soal ${idx+1}" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">` : ''}
            <div style="font-size: 0.75rem; font-weight: 800; color: #0369A1; margin-bottom: 0.3rem;">${escapeHtml(q.topic || `SOAL ${idx+1}:`)}</div>
            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.4rem;">${escapeHtml(q.question)}</h3>
            <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 0.85rem;">${escapeHtml(q.subtitle || 'Pilih jawaban yang tepat!')}</p>
            
            <div style="display: flex; flex-direction: column; gap: 0.6rem;" class="mc-options-container" data-qindex="${idx}">
              ${(q.options || []).map(opt => `
                <button type="button" class="checkbox-option-card" data-val="${opt.id}" data-correct="${opt.correct}">
                  <div class="checkbox-icon"></div>
                  <div style="text-align: left; flex: 1;">${escapeHtml(opt.text)}</div>
                </button>
              `).join('')}
            </div>

            <div class="feedback-box mc-feedback" id="mc-feedback-${idx}" style="display: none; margin-top: 0.85rem;"></div>

            <button type="button" class="btn-kid btn-kid-blue btn-check-mc" data-qindex="${idx}" style="margin-top: 0.85rem;">
              <i class="fas fa-check-circle"></i> Cek Jawaban!
            </button>
            <button type="button" class="btn-kid btn-kid-green btn-next-mc" data-qindex="${idx}" style="margin-top: 0.85rem; display: none;">
              Lanjut <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        `).join('')}
      </div>
    `;
  }

  function initMultichoiceLogic(lvl, content) {
    const questions = content.questions || [];
    let currentQ = 0, errorsCount = 0;

    // Toggle button style
    document.querySelectorAll('.mc-options-container .checkbox-option-card').forEach(card => {
      card.addEventListener('click', (e) => {
        e.preventDefault();
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
      });
    });

    document.querySelectorAll('.btn-check-mc').forEach(btn => {
      btn.addEventListener('click', () => {
        const qIdx = parseInt(btn.getAttribute('data-qindex'));
        const q = questions[qIdx];
        const container = document.querySelector(`.mc-options-container[data-qindex="${qIdx}"]`);
        const selectedCards = container.querySelectorAll('.checkbox-option-card.selected');
        const feedbackEl = document.getElementById(`mc-feedback-${qIdx}`);
        const btnNext = document.querySelector(`.btn-next-mc[data-qindex="${qIdx}"]`);

        if (selectedCards.length === 0) {
          showToast('Pilih setidaknya 1 jawaban!', 'warning');
          return;
        }

        const selectedVals = Array.from(selectedCards).map(c => c.getAttribute('data-val'));
        const correctVals = (q.options || []).filter(o => o.correct).map(o => String(o.id));

        const isExactMatch = selectedVals.length === correctVals.length && selectedVals.every(v => correctVals.includes(v));

        feedbackEl.style.display = 'block';
        if (isExactMatch) {
          ecoSound.playCorrect();
          showToast('Jawaban Tepat!', 'success');
          feedbackEl.className = 'feedback-box success';
          feedbackEl.innerHTML = (q.feedback_correct || '🎉 Sempurna! Jawaban kamu benar semua!').replace(/\n/g, '<br>');
          btn.style.display = 'none';
          btnNext.style.display = 'block';
        } else {
          ecoSound.playWrong();
          errorsCount++;
          showToast('Kurang tepat, coba teliti lagi!', 'error');
          feedbackEl.className = 'feedback-box error';
          feedbackEl.innerHTML = (q.feedback_wrong || '⚠️ Masih ada pilihan yang kurang pas! Pilihlah jawaban yang paling tepat.').replace(/\n/g, '<br>');
        }
      });
    });

    document.querySelectorAll('.btn-next-mc').forEach(btn => {
      btn.addEventListener('click', () => {
        const qIdx = parseInt(btn.getAttribute('data-qindex'));
        document.getElementById(`mc-q-card-${qIdx}`).style.display = 'none';
        if (qIdx + 1 < questions.length) {
          currentQ = qIdx + 1;
          document.getElementById(`mc-q-card-${currentQ}`).style.display = 'block';
          document.getElementById('q-step-text').textContent = `SOAL ${currentQ + 1} DARI ${questions.length}`;
          document.getElementById('q-progress-dots').textContent = '🔵 '.repeat(currentQ + 1) + '⚪ '.repeat(questions.length - (currentQ + 1));
        } else {
          finishLevel(lvl.level_number, errorsCount);
        }
      });
    });
  }

  // 3. FILTER GAME RENDERER & LOGIC
  function renderFilterGame(lvl, content) {
    const scenarios = content.scenarios || [];
    return `
      <div class="kid-card" id="scenario-card" style="border-color: ${lvl.bg_color};">
        ${lvl.illustration ? `<img src="${escapeHtml(lvl.illustration)}" alt="Solusi" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">` : ''}
        <div style="font-size: 0.72rem; font-weight: 800; color: #B45309; margin-bottom: 0.3rem;" id="scenario-step">SKENARIO 1 DARI ${scenarios.length}</div>
        <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.6rem;" id="scenario-title">Memuat...</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;" id="scenario-desc">Memuat...</p>
        <div style="font-weight: 800; font-size: 0.85rem; color: #0284C7; margin-bottom: 0.5rem;">💡 Filter & Pilih Solusi Paling Utama & Penting:</div>
        <div id="filter-options-container" style="display: flex; flex-direction: column; gap: 0.5rem;"></div>
        <div id="filter-feedback" class="feedback-box" style="display: none; margin-top: 0.85rem;"></div>
      </div>
    `;
  }

  function initFilterLogic(lvl, content) {
    const scenarios = content.scenarios || [];
    let currentIndex = 0, errorsCount = 0;

    function renderCurScenario() {
      if (currentIndex >= scenarios.length) {
        finishLevel(lvl.level_number, errorsCount);
        return;
      }
      const sc = scenarios[currentIndex];
      document.getElementById('scenario-step').textContent = `SKENARIO ${currentIndex + 1} DARI ${scenarios.length}`;
      document.getElementById('scenario-title').textContent = sc.title;
      document.getElementById('scenario-desc').textContent = sc.desc;
      const feedbackEl = document.getElementById('filter-feedback');
      feedbackEl.style.display = 'none';

      const container = document.getElementById('filter-options-container');
      container.innerHTML = '';
      (sc.options || []).forEach(opt => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-kid';
        btn.style.cssText = 'background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.88rem; text-align: left; padding: 0.8rem 1rem; border-radius: 16px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;';
        btn.textContent = opt.text;

        btn.addEventListener('click', () => {
          feedbackEl.style.display = 'block';
          if (opt.correct) {
            ecoSound.playCorrect();
            showToast('Pilihan Solusi Utama Tepat!', 'success');
            feedbackEl.className = 'feedback-box success';
            feedbackEl.textContent = opt.feedback || '🎉 Tepat Sekali!';
            btn.style.cssText = 'background: #DCFCE7; border: 3px solid #22C55E; color: #15803D; font-size: 0.88rem; text-align: left; padding: 0.8rem 1rem; border-radius: 16px; width: 100%; box-shadow: 0 4px 0 #15803D; font-weight: 800;';
            setTimeout(() => {
              currentIndex++;
              renderCurScenario();
            }, 1800);
          } else {
            ecoSound.playWrong();
            showToast('Solusi ini kurang tepat', 'error');
            errorsCount++;
            feedbackEl.className = 'feedback-box error';
            feedbackEl.textContent = opt.feedback || '⚠️ Pilihan ini kurang efektif.';
            btn.style.cssText = 'background: #FEE2E2; border: 3px solid #EF4444; color: #991B1B; font-size: 0.88rem; text-align: left; padding: 0.8rem 1rem; border-radius: 16px; width: 100%; box-shadow: 0 4px 0 #B91C1C;';
          }
        });
        container.appendChild(btn);
      });
    }
    renderCurScenario();
  }

  // 4. SEQUENCE GAME RENDERER & LOGIC
  function renderSequenceGame(lvl, content) {
    return `
      <div class="kid-card" style="border-color: ${lvl.bg_color};">
        ${lvl.illustration ? `<img src="${escapeHtml(lvl.illustration)}" alt="Urutan Langkah" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">` : ''}
        <div style="font-size: 0.8rem; font-weight: 800; color: #6D28D9; margin-bottom: 0.35rem;">SOAL PENANGANAN SAMPAH:</div>
        <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.4rem;">${escapeHtml(content.problem_title || 'Masalah Penanganan Sampah')}</h3>
        <p style="font-size: 0.85rem; font-weight: 700; color: #0284C7; margin-bottom: 1rem;">${escapeHtml(lvl.instructions || 'Pilih urutan langkah yang paling tepat!')}</p>
        <div id="sequence-options-container" style="display: flex; flex-direction: column; gap: 0.65rem;">
          ${(content.options || []).map(opt => `
            <button type="button" class="btn-kid seq-option-btn" data-seq="${opt.seq_id}" data-correct="${opt.correct}" style="background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;">
              <span class="seq-badge" style="display: inline-block; width: 28px; height: 28px; background: #F1F5F9; color: var(--text-dark); border-radius: 50%; text-align: center; line-height: 28px; font-weight: 800; margin-right: 0.4rem;">${escapeHtml(opt.badge || '•')}</span>
              ${escapeHtml(opt.text)}
            </button>
          `).join('')}
        </div>
        <div id="sequence-feedback" class="feedback-box" style="display: none; margin-top: 1rem;"></div>
        <button type="button" class="btn-kid btn-kid-green" id="btn-next-seq" style="margin-top: 1rem; display: none;">
          Selesaikan Level <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    `;
  }

  function initSequenceLogic(lvl, content) {
    let errorsCount = 0;
    const buttons = document.querySelectorAll('.seq-option-btn');
    const feedbackEl = document.getElementById('sequence-feedback');
    const btnNext = document.getElementById('btn-next-seq');

    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const isCorrect = btn.getAttribute('data-correct') === 'true';
        buttons.forEach(b => {
          b.style.cssText = 'background: #FFFFFF !important; border: 3px solid #CBD5E1 !important; color: #0F172A !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1 !important;';
        });

        feedbackEl.style.display = 'block';
        if (isCorrect) {
          ecoSound.playCorrect();
          showToast('Urutan langkah 100% tepat!', 'success');
          btn.style.cssText = 'background: linear-gradient(135deg, #22C55E, #16A34A) !important; border: 3px solid #15803D !important; color: #FFFFFF !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 5px 0 #14532D !important;';
          feedbackEl.className = 'feedback-box success';
          feedbackEl.innerHTML = (content.feedback_correct || '🎉 Sempurna! Urutan langkah tepat.').replace(/\n/g, '<br>');
          btnNext.style.display = 'block';
        } else {
          ecoSound.playWrong();
          errorsCount++;
          showToast('Urutan kurang tepat!', 'error');
          btn.style.cssText = 'background: #FEE2E2 !important; border: 3px solid #EF4444 !important; color: #991B1B !important; font-size: 0.95rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 5px 0 #B91C1C !important;';
          feedbackEl.className = 'feedback-box error';
          feedbackEl.innerHTML = (content.feedback_wrong || '⚠️ Urutan belum pas, coba pilihan lain.').replace(/\n/g, '<br>');
        }
      });
    });

    btnNext.addEventListener('click', () => {
      finishLevel(lvl.level_number, errorsCount);
    });
  }

  // 5. DECISION GAME RENDERER & LOGIC
  function renderDecisionGame(lvl, content) {
    const scenarios = content.scenarios || [];
    return `
      <div class="kid-card" id="decision-card" style="border-color: ${lvl.bg_color};">
        ${lvl.illustration ? `<img src="${escapeHtml(lvl.illustration)}" alt="Decision Game" style="width: 100%; max-height: 160px; object-fit: cover; border-radius: 14px; margin-bottom: 0.75rem; border: 2px solid #CBD5E1;">` : ''}
        <div style="font-size: 0.72rem; font-weight: 800; color: #DB2777; margin-bottom: 0.3rem;" id="decision-step">SKENARIO 1 DARI ${scenarios.length}</div>
        <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 0.5rem;" id="decision-title">Memuat...</h3>
        <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1rem;" id="decision-desc">Memuat...</p>
        <div style="font-weight: 800; font-size: 0.85rem; color: #15803D; margin-bottom: 0.6rem;">🌱 Pilih Tindakan Nyata Yang Paling Berdampak Positif Bagi Bumi:</div>
        <div id="choices-container" style="display: flex; flex-direction: column; gap: 0.6rem;"></div>
        <div id="decision-feedback" class="feedback-box" style="display: none; margin-top: 0.85rem;"></div>
      </div>
    `;
  }

  function initDecisionLogic(lvl, content) {
    const scenarios = content.scenarios || [];
    let currentIndex = 0, errorsCount = 0;

    function renderCurDecision() {
      if (currentIndex >= scenarios.length) {
        finishLevel(lvl.level_number, errorsCount);
        return;
      }
      const dec = scenarios[currentIndex];
      document.getElementById('decision-step').textContent = `SKENARIO ${currentIndex + 1} DARI ${scenarios.length}`;
      document.getElementById('decision-title').textContent = dec.title;
      document.getElementById('decision-desc').textContent = dec.desc;

      const choicesContainer = document.getElementById('choices-container');
      const feedbackEl = document.getElementById('decision-feedback');
      feedbackEl.style.display = 'none';
      choicesContainer.innerHTML = '';

      (dec.choices || []).forEach(ch => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn-kid';
        btn.style.cssText = 'background: #FFFFFF; border: 3px solid #CBD5E1; color: var(--text-dark); font-size: 0.9rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #CBD5E1;';
        btn.textContent = ch.text;

        btn.addEventListener('click', () => {
          feedbackEl.style.display = 'block';
          if (ch.isBest) {
            ecoSound.playCorrect();
            showToast('Tindakan Ramah Lingkungan!', 'success');
            feedbackEl.className = 'feedback-box success';
            feedbackEl.textContent = ch.msg || '🎉 Sempurna!';
            btn.style.cssText = 'background: #DCFCE7; border: 3px solid #22C55E; color: #15803D; font-size: 0.9rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #15803D; font-weight: 800;';
            setTimeout(() => {
              currentIndex++;
              renderCurDecision();
            }, 1800);
          } else {
            ecoSound.playWrong();
            showToast('Tindakan ini kurang tepat', 'error');
            errorsCount++;
            feedbackEl.className = 'feedback-box error';
            feedbackEl.textContent = ch.msg || '⚠️ Kurang tepat!';
            btn.style.cssText = 'background: #FEE2E2; border: 3px solid #EF4444; color: #991B1B; font-size: 0.9rem; text-align: left; padding: 0.9rem 1.1rem; border-radius: 18px; width: 100%; box-shadow: 0 4px 0 #B91C1C;';
          }
        });
        choicesContainer.appendChild(btn);
      });
    }
    renderCurDecision();
  }

  // FINISH LEVEL & SAVE PROGRESS
  function finishLevel(rawLevelNumber, errorsCount) {
    const curLevel = parseInt(rawLevelNumber) || 1;
    const nextLevel = curLevel + 1;

    let stars = 3;
    if (errorsCount === 1) stars = 2;
    else if (errorsCount >= 2) stars = 1;

    const baseScore = 500;
    const finalScore = Math.max(100, baseScore - (errorsCount * 100));

    ecoSound.playLevelComplete();

    document.getElementById('res-stars').textContent = '⭐'.repeat(stars) + '☆'.repeat(3 - stars);
    document.getElementById('res-score').textContent = finalScore;

    const btnNext = document.getElementById('btn-next-level');
    btnNext.href = `level.php?id=${nextLevel}`;
    btnNext.innerHTML = `Lanjut Level ${nextLevel} <i class="fas fa-arrow-right"></i>`;

    document.getElementById('modal-level-result').style.display = 'flex';

    // Submit level score to backend API
    fetch('api/index.php?action=save_level_result', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        access_key: userKey,
        level_number: curLevel,
        score: finalScore,
        stars: stars,
        stars_earned: stars,
        high_score: finalScore
      })
    })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        showToast(`Skor Level ${curLevel} (${finalScore} poin) tersimpan & Level ${nextLevel} terbuka!`, 'success');
      }
    })
    .catch(e => console.error(e));
  }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
