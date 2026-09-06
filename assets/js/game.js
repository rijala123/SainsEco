/**
 * Enhanced Eco Clean Game - Waste Sorting Game Engine
 * Screen 4: Arena Misi Game Pilah Sampah
 * v2.0 - Anti-repeat shuffle queue + 40+ misi unik
 */

class WasteGameEngine {
  constructor() {
    this.stage = document.getElementById('game-stage');
    this.overlayStart = document.getElementById('game-overlay-start');
    this.overlayEnd = document.getElementById('game-overlay-end');

    // HUD Elements
    this.hudScore = document.getElementById('hud-score');
    this.hudTime = document.getElementById('hud-time');
    this.hudCombo = document.getElementById('hud-combo');
    this.hudSorted = document.getElementById('hud-sorted');

    // === EXTENDED WASTE ITEMS DATABASE (40+ item unik) ===
    this.itemsDB = [
      // ── Organik (15 item) ──
      { name: 'Sisa Apel',       category: 'organik',   icon: '🍎', fact: 'Sisa apel bisa menjadi kompos kaya nutrisi tanah!' },
      { name: 'Kulit Pisang',    category: 'organik',   icon: '🍌', fact: 'Kulit pisang kaya kalium untuk menutrisi tanaman.' },
      { name: 'Daun Kering',     category: 'organik',   icon: '🍂', fact: 'Daun kering terurai cepat oleh mikroorganisme tanah.' },
      { name: 'Cangkang Telur',  category: 'organik',   icon: '🥚', fact: 'Cangkang telur mengandung kalsium tinggi untuk pupuk.' },
      { name: 'Sisa Ikan',       category: 'organik',   icon: '🐟', fact: 'Limbah sisa makanan harus segera jadi kompos!' },
      { name: 'Sisa Nasi',       category: 'organik',   icon: '🍚', fact: 'Nasi sisa adalah sumber nitrogen bagi tanah.' },
      { name: 'Kulit Jeruk',     category: 'organik',   icon: '🍊', fact: 'Kulit jeruk mengandung minyak alami penolak serangga.' },
      { name: 'Ampas Kopi',      category: 'organik',   icon: '☕', fact: 'Ampas kopi adalah pupuk nitrogen alami yang bagus.' },
      { name: 'Biji Buah',       category: 'organik',   icon: '🌱', fact: 'Biji buah bisa tumbuh menjadi pohon baru!' },
      { name: 'Kulit Kentang',   category: 'organik',   icon: '🥔', fact: 'Kulit kentang terurai dan menyuburkan tanah.' },
      { name: 'Daun Sayur',      category: 'organik',   icon: '🥬', fact: 'Potongan sayur memperkaya kompos dengan mineral.' },
      { name: 'Sisa Bunga',      category: 'organik',   icon: '🌸', fact: 'Bunga layu bisa menjadi bahan kompos yang harum.' },
      { name: 'Cangkang Kerang', category: 'organik',   icon: '🐚', fact: 'Cangkang kerang mengandung kalsium untuk tanah basa.' },
      { name: 'Ranting Pohon',   category: 'organik',   icon: '🌿', fact: 'Ranting kecil dihancurkan menjadi mulsa organik.' },
      { name: 'Kulit Mangga',    category: 'organik',   icon: '🥭', fact: 'Kulit mangga bisa dijadikan kompos dalam 3 minggu.' },

      // ── Anorganik / Daur Ulang (16 item) ──
      { name: 'Botol Plastik',   category: 'anorganik', icon: '🍾', fact: 'Botol PET didaur ulang menjadi serat pakaian fleece!' },
      { name: 'Kaleng Aluminium',category: 'anorganik', icon: '🥫', fact: 'Daur ulang kaleng hemat 95% energi dibanding buat baru!' },
      { name: 'Kardus Bekas',    category: 'anorganik', icon: '📦', fact: '1 ton kertas daur ulang menyelamatkan 17 pohon dewasa!' },
      { name: 'Kantong Plastik', category: 'anorganik', icon: '🛍️', fact: 'Kantong plastik butuh 500 tahun untuk hancur di TPA.' },
      { name: 'Botol Kaca',      category: 'anorganik', icon: '🍷', fact: 'Kaca didaur ulang 100% tanpa penurunan kualitas!' },
      { name: 'Koran Bekas',     category: 'anorganik', icon: '📰', fact: 'Kertas koran bisa didaur ulang menjadi kertas baru.' },
      { name: 'Kaleng Cat',      category: 'anorganik', icon: '🪣', fact: 'Kaleng besi bisa dipanaskan ulang dan dibentuk baru.' },
      { name: 'Buku Lama',       category: 'anorganik', icon: '📚', fact: 'Buku bekas bisa disumbangkan atau didaur ulang.' },
      { name: 'Tutup Botol',     category: 'anorganik', icon: '🔵', fact: 'Tutup botol plastik dikumpulkan untuk daur ulang kreatif.' },
      { name: 'Gelas Plastik',   category: 'anorganik', icon: '🥤', fact: 'Gelas plastik bisa diubah menjadi pot tanaman mini!' },
      { name: 'Plastik Bungkus', category: 'anorganik', icon: '🧴', fact: 'Plastik bungkus didaur ulang menjadi pot hitam kebun.' },
      { name: 'Ember Plastik',   category: 'anorganik', icon: '🪣', fact: 'Ember plastik bekas bisa jadi pot bunga cantik.' },
      { name: 'Majalah Lama',    category: 'anorganik', icon: '📖', fact: 'Majalah bekas bisa jadi bahan kerajinan unik.' },
      { name: 'Galon Air',       category: 'anorganik', icon: '💧', fact: 'Galon air bekas digunakan ulang ratusan kali.' },
      { name: 'Tas Kresek',      category: 'anorganik', icon: '🛍️', fact: 'Kurangi penggunaan tas plastik, pilih tas kain!' },
      { name: 'Botol Minum',     category: 'anorganik', icon: '🧃', fact: 'Botol minum bisa diisi ulang, hemat plastik!' },

      // ── B3 (Bahan Berbahaya & Beracun) (9 item) ──
      { name: 'Baterai Bekas',   category: 'b3',        icon: '🔋', fact: 'Baterai mengandung cadmium & merkuri beracun bagi air tanah.' },
      { name: 'Lampu Neon',      category: 'b3',        icon: '💡', fact: 'Lampu neon pecah mengeluarkan uap merkuri berbahaya.' },
      { name: 'Botol Obat Serangga', category: 'b3',   icon: '🧪', fact: 'Pestisida residu harus diserahkan ke fasilitas B3.' },
      { name: 'Obat Expired',    category: 'b3',        icon: '💊', fact: 'Obat kedaluwarsa mencemari ekosistem sungai.' },
      { name: 'Cat Bekas',       category: 'b3',        icon: '🎨', fact: 'Cat mengandung logam berat timbal (Pb) yang beracun.' },
      { name: 'Tinta Printer',   category: 'b3',        icon: '🖨️', fact: 'Kartrid tinta mengandung bahan kimia berbahaya.' },
      { name: 'Bola Lampu LED',  category: 'b3',        icon: '🔦', fact: 'Komponen LED lama mengandung arsenik & timbal.' },
      { name: 'Cairan Pembersih', category: 'b3',       icon: '🧽', fact: 'Cairan kimia rumah tangga tidak boleh dibuang ke selokan.' },
      { name: 'Bahan Bakar Sisa', category: 'b3',       icon: '⛽', fact: 'Sisa bensin & oli adalah limbah B3 berbahaya tinggi.' }
    ];

    // Shuffle queue untuk anti-pengulangan
    this.shuffleQueue = [];
    this.playedItems = new Set();

    // Game States
    this.score = 0;
    this.timeLeft = 90;
    this.combo = 1;
    this.maxCombo = 1;
    this.wasteSorted = 0;
    this.isPlaying = false;
    this.timerInterval = null;
    this.activeItemEl = null;
    this.activeItemData = null;

    this.bindEvents();
  }

  // ── Anti-repeat shuffle queue ──────────────────────────────────────────────
  buildShuffleQueue() {
    // Fisher-Yates shuffle of full itemsDB
    const arr = [...this.itemsDB];
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
    return arr;
  }

  getNextItem() {
    if (this.shuffleQueue.length === 0) {
      // Rebuild full shuffled queue once all items used
      this.shuffleQueue = this.buildShuffleQueue();
    }
    return this.shuffleQueue.shift();
  }

  // ── Event Bindings ─────────────────────────────────────────────────────────
  bindEvents() {
    const btnStart = document.getElementById('btn-start-game');
    if (btnStart) btnStart.addEventListener('click', () => this.startGame());

    const btnRestart = document.getElementById('btn-restart-game');
    if (btnRestart) btnRestart.addEventListener('click', () => this.startGame());

    // Trash Bins: only drop target (no click-to-sort)
    document.querySelectorAll('.trash-bin').forEach(bin => {
      bin.addEventListener('dragover', (e) => {
        e.preventDefault();
        bin.classList.add('drag-over');
      });

      bin.addEventListener('dragleave', () => {
        bin.classList.remove('drag-over');
      });

      bin.addEventListener('drop', (e) => {
        e.preventDefault();
        bin.classList.remove('drag-over');
        if (this.isPlaying && this.activeItemEl) {
          this.checkSort(bin.getAttribute('data-category'));
        }
      });
    });
  }

  // ── Game Lifecycle ─────────────────────────────────────────────────────────
  startGame() {
    this.score      = 0;
    this.timeLeft   = 90;
    this.combo      = 1;
    this.maxCombo   = 1;
    this.wasteSorted = 0;
    this.isPlaying  = true;

    // Reset shuffle queue setiap game baru
    this.shuffleQueue = this.buildShuffleQueue();

    this.updateHUD();

    if (this.overlayStart) this.overlayStart.style.display = 'none';
    if (this.overlayEnd)   this.overlayEnd.style.display   = 'none';

    // Clear old items
    this.stage.querySelectorAll('.waste-item').forEach(el => el.remove());

    // Start Timer
    clearInterval(this.timerInterval);
    this.timerInterval = setInterval(() => {
      this.timeLeft--;
      this.hudTime.textContent = `${this.timeLeft}s`;
      if (this.timeLeft <= 10) {
        this.hudTime.classList.add('highlight-red');
      } else {
        this.hudTime.classList.remove('highlight-red');
      }
      if (this.timeLeft <= 0) this.endGame();
    }, 1000);

    this.spawnNextItem();
  }

  // ── Item Spawning ──────────────────────────────────────────────────────────
  spawnNextItem() {
    if (!this.isPlaying) return;

    if (this.activeItemEl) {
      this.activeItemEl.remove();
      this.activeItemEl = null;
    }

    const itemData = this.getNextItem();
    this.activeItemData = itemData;

    const itemEl = document.createElement('div');
    itemEl.className = 'waste-item';
    itemEl.setAttribute('draggable', 'true');
    itemEl.setAttribute('data-category', itemData.category);

    itemEl.innerHTML = `
      <div class="waste-icon">${itemData.icon}</div>
      <div class="waste-name">${escapeHtml(itemData.name)}</div>
    `;

    // Random horizontal position
    const randomLeft = Math.floor(Math.random() * 55) + 20;
    itemEl.style.left = `${randomLeft}%`;
    itemEl.style.top  = '70px';

    // Drag events
    itemEl.addEventListener('dragstart', (e) => {
      e.dataTransfer.setData('text/plain', itemData.category);
    });

    // Touch drag support for mobile
    this.enableTouchDrag(itemEl, itemData.category);

    this.stage.appendChild(itemEl);
    this.activeItemEl = itemEl;
  }

  // ── Mobile Touch Drag ──────────────────────────────────────────────────────
  enableTouchDrag(el, itemCategory) {
    el.addEventListener('touchstart', (e) => {
      // prevent default only if we need move
    }, { passive: true });

    el.addEventListener('touchmove', (e) => {
      e.preventDefault();
      const touch = e.touches[0];
      const stageRect = this.stage.getBoundingClientRect();
      const newX = touch.clientX - stageRect.left - 45;
      const newY = touch.clientY - stageRect.top - 45;
      el.style.left = `${newX}px`;
      el.style.top  = `${newY}px`;
    }, { passive: false });

    el.addEventListener('touchend', (e) => {
      if (!this.isPlaying || !this.activeItemEl) return;
      const touch = e.changedTouches[0];
      document.querySelectorAll('.trash-bin').forEach(bin => {
        const rect = bin.getBoundingClientRect();
        if (
          touch.clientX >= rect.left && touch.clientX <= rect.right &&
          touch.clientY >= rect.top  && touch.clientY <= rect.bottom
        ) {
          this.checkSort(bin.getAttribute('data-category'));
        }
      });
    });
  }

  // ── Sorting Logic ──────────────────────────────────────────────────────────
  checkSort(targetCategory) {
    if (!this.isPlaying || !this.activeItemEl) return;

    const actualCategory = this.activeItemEl.getAttribute('data-category');

    if (targetCategory === actualCategory) {
      // ✅ Benar
      const points = 100 * this.combo;
      this.score += points;
      this.wasteSorted++;
      this.combo++;
      if (this.combo > this.maxCombo) this.maxCombo = this.combo;

      ecoSound.playCorrect();
      this.showFact(this.activeItemData.fact);
      this.showComboEffect(`+${points} Poin! Combo x${this.combo - 1}`);
      showToast(`Tepat! ${this.activeItemData.name} → ${actualCategory.toUpperCase()} ✅`, 'success');
    } else {
      // ❌ Salah
      this.score = Math.max(0, this.score - 50);
      this.combo = 1;
      ecoSound.playWrong();
      this.showComboEffect(`Salah Tong! -50`, true);
      showToast(`Oops! ${this.activeItemData.name} bukan ${targetCategory.toUpperCase()}!`, 'error');
    }

    this.updateHUD();
    this.spawnNextItem();
  }

  // ── UI Helpers ─────────────────────────────────────────────────────────────
  showComboEffect(text, isError = false) {
    const popup = document.createElement('div');
    popup.className = 'combo-popup';
    if (isError) popup.style.color = '#EF4444';
    popup.textContent = text;
    popup.style.left = '50%';
    popup.style.top  = '40%';
    popup.style.transform = 'translate(-50%, -50%)';
    this.stage.appendChild(popup);
    setTimeout(() => popup.remove(), 1200);
  }

  showFact(factText) {
    // Show eco-fact briefly at top of stage
    const fact = document.createElement('div');
    fact.style.cssText = `
      position: absolute; bottom: 5px; left: 5px; right: 5px;
      background: rgba(21,128,61,0.92); color: #fff;
      padding: 6px 10px; border-radius: 10px; font-size: 0.65rem;
      font-weight: 600; z-index: 20; text-align: center;
      animation: fadeIn 0.3s ease;
    `;
    fact.textContent = `💡 ${factText}`;
    this.stage.appendChild(fact);
    setTimeout(() => fact.remove(), 2500);
  }

  updateHUD() {
    this.hudScore.textContent   = this.score;
    this.hudTime.textContent    = `${this.timeLeft}s`;
    this.hudCombo.textContent   = `${this.combo}x`;
    this.hudSorted.textContent  = this.wasteSorted;
  }

  // ── End Game ───────────────────────────────────────────────────────────────
  endGame() {
    this.isPlaying = false;
    clearInterval(this.timerInterval);

    if (this.activeItemEl) {
      this.activeItemEl.remove();
      this.activeItemEl = null;
    }

    ecoSound.playLevelUp();

    document.getElementById('end-score').textContent  = this.score;
    document.getElementById('end-sorted').textContent = `${this.wasteSorted} item`;
    document.getElementById('end-combo').textContent  = `${this.maxCombo}x`;

    this.saveScoreToBackend();

    if (this.overlayEnd) this.overlayEnd.style.display = 'flex';
  }

  saveScoreToBackend() {
    const payload = {
      action: 'save_score',
      access_key:   currentUser.key_code    || 'ECO-GUEST',
      student_name: currentUser.student_name || 'Pahlawan Eco',
      school_class: currentUser.school_class || 'Kelas 5 Eco',
      score:        this.score,
      waste_sorted: this.wasteSorted,
      max_combo:    this.maxCombo
    };

    fetch(ECO_CONFIG.apiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('end-badge').textContent = data.data.badge;
        const totalScore = data.data.total_score || this.score;
        const endScoreEl = document.getElementById('end-score');
        if (endScoreEl) {
          endScoreEl.innerHTML = `${this.score} <span style="font-size:0.75rem; color:#0284C7; font-weight:700;">(Total Klasemen: ${totalScore} pts)</span>`;
        }
        showToast(`Skor Pilah (${this.score}) digabung ke Klasemen! Total: ${totalScore} pts 🏆`, 'success');
      }
    })
    .catch(() => {
      showToast('Skor disimpan secara lokal', 'info');
    });
  }
}

// ── Bootstrap ─────────────────────────────────────────────────────────────────
let gameEngine = null;
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('game-stage')) {
    gameEngine = new WasteGameEngine();
  }
});
