<?php
$pageTitle = "Arena Misi - Eco Clean App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- HP HUD Header -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.35rem; margin-bottom: 0.75rem;">
  <div class="kid-card" style="padding: 0.5rem 0.2rem; text-align: center; margin: 0;">
    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">SKOR</div>
    <div id="hud-score" style="font-size: 1.15rem; font-weight: 900; color: #22C55E;">0</div>
  </div>

  <div class="kid-card" style="padding: 0.5rem 0.2rem; text-align: center; margin: 0;">
    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">WAKTU</div>
    <div id="hud-time" style="font-size: 1.15rem; font-weight: 900; color: #0284C7;">90s</div>
  </div>

  <div class="kid-card" style="padding: 0.5rem 0.2rem; text-align: center; margin: 0;">
    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">COMBO</div>
    <div id="hud-combo" style="font-size: 1.15rem; font-weight: 900; color: #F59E0B;">1x</div>
  </div>

  <div class="kid-card" style="padding: 0.5rem 0.2rem; text-align: center; margin: 0;">
    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted);">DIPILAH</div>
    <div id="hud-sorted" style="font-size: 1.15rem; font-weight: 900; color: var(--text-dark);">0</div>
  </div>
</div>

<!-- HP Game Arena Stage -->
<div class="hp-game-stage" id="game-stage">

  <!-- Start Overlay -->
  <div id="game-overlay-start" style="position: absolute; inset: 0; background: rgba(255,255,255,0.96); z-index: 30; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.25rem; text-align: center;">
    <div style="font-size: 3rem; margin-bottom: 0.3rem;">🎮🌍</div>
    <h2 style="font-size: 1.4rem; color: var(--text-dark); margin-bottom: 0.3rem;">Misi Pilah Sampah!</h2>
    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem; line-height: 1.5;">
      Seret sampah yang muncul ke tong yang <strong>benar</strong>!<br>
      40+ jenis sampah unik — tidak ada yang terulang!
    </p>

    <div style="display: flex; gap: 0.3rem; flex-wrap: wrap; justify-content: center; margin-bottom: 1.2rem;">
      <span style="padding: 0.25rem 0.5rem; background: #DCFCE7; color: #15803D; font-weight: 800; font-size: 0.72rem; border-radius: 12px;">🍃 Organik</span>
      <span style="padding: 0.25rem 0.5rem; background: #FEF3C7; color: #B45309; font-weight: 800; font-size: 0.72rem; border-radius: 12px;">🥤 Anorganik</span>
      <span style="padding: 0.25rem 0.5rem; background: #FEE2E2; color: #B91C1C; font-weight: 800; font-size: 0.72rem; border-radius: 12px;">🔋 B3</span>
    </div>

    <!-- Mission stats preview -->
    <div style="display: grid; grid-template-columns: repeat(3,1fr); gap: 0.4rem; width: 100%; margin-bottom: 1.2rem;">
      <div style="background: #F0FDF4; border: 1.5px solid #22C55E; border-radius: 10px; padding: 0.45rem; text-align:center;">
        <div style="font-size: 1.1rem;">🍎</div>
        <div style="font-size: 0.62rem; font-weight: 800; color: #15803D;">15 Organik</div>
      </div>
      <div style="background: #FFFBEB; border: 1.5px solid #F59E0B; border-radius: 10px; padding: 0.45rem; text-align:center;">
        <div style="font-size: 1.1rem;">📦</div>
        <div style="font-size: 0.62rem; font-weight: 800; color: #B45309;">16 Anorganik</div>
      </div>
      <div style="background: #FFF1F2; border: 1.5px solid #EF4444; border-radius: 10px; padding: 0.45rem; text-align:center;">
        <div style="font-size: 1.1rem;">🔋</div>
        <div style="font-size: 0.62rem; font-weight: 800; color: #B91C1C;">9 Berbahaya</div>
      </div>
    </div>

    <button class="btn-kid btn-kid-green" id="btn-start-game" style="font-size: 1.1rem; padding: 0.85rem 1.8rem;">
      <i class="fas fa-play"></i> Mulai Main!
    </button>
  </div>

  <!-- End Game Overlay -->
  <div id="game-overlay-end" style="position: absolute; inset: 0; background: rgba(255,255,255,0.96); z-index: 30; display: none; flex-direction: column; align-items: center; justify-content: center; padding: 1.25rem; text-align: center;">
    <div style="font-size: 3rem; margin-bottom: 0.2rem;">🏆🎉</div>
    <h2 style="font-size: 1.4rem; color: var(--text-dark); margin-bottom: 0.2rem;">Misi Selesai!</h2>
    <p style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.85rem;">Skor kamu disimpan di Klasemen Pahlawan!</p>

    <div style="width: 100%; background: #F8FAFC; border: 2px solid #E2E8F0; border-radius: 16px; padding: 0.85rem; margin-bottom: 1rem; text-align: left; font-size: 0.82rem;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
        <span style="color: var(--text-muted);">Skor Total:</span>
        <strong id="end-score" style="color: #22C55E; font-size: 1.1rem;">0</strong>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
        <span style="color: var(--text-muted);">Sampah Dipilah:</span>
        <strong id="end-sorted">0 item</strong>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem;">
        <span style="color: var(--text-muted);">Combo Max:</span>
        <strong id="end-combo" style="color: #F59E0B;">1x</strong>
      </div>
      <div style="display: flex; justify-content: space-between; border-top: 1px dashed #CBD5E1; padding-top: 0.3rem; margin-top: 0.3rem;">
        <span style="color: var(--text-muted);">Lencana Diraih:</span>
        <strong id="end-badge" style="color: #B45309;">Pahlawan Pemilah</strong>
      </div>
    </div>

    <div style="display: flex; gap: 0.5rem; width: 100%;">
      <button class="btn-kid btn-kid-green" id="btn-restart-game" style="flex: 1; padding: 0.75rem;">
        <i class="fas fa-redo"></i> Main Lagi
      </button>
      <a href="klasemen.php" class="btn-kid btn-kid-yellow" style="flex: 1; padding: 0.75rem;">
        <i class="fas fa-trophy"></i> Klasemen
      </a>
    </div>
  </div>

  <!-- Trash Bins Row HP (DRAG TARGET ONLY — no click to sort) -->
  <div class="hp-bins-row">
    <!-- Bin 1: Organik -->
    <div class="hp-trash-bin organik trash-bin" data-category="organik">
      <div class="bin-face">🍃</div>
      <div class="bin-label" style="color: var(--organic-color);">ORGANIK</div>
    </div>

    <!-- Bin 2: Anorganik -->
    <div class="hp-trash-bin anorganik trash-bin" data-category="anorganik">
      <div class="bin-face">🥤</div>
      <div class="bin-label" style="color: var(--inorganic-color);">ANORGANIK</div>
    </div>

    <!-- Bin 3: B3 -->
    <div class="hp-trash-bin b3 trash-bin" data-category="b3">
      <div class="bin-face">🔋</div>
      <div class="bin-label" style="color: var(--b3-color);">B3</div>
    </div>
  </div>
</div>

<!-- Tips Card -->
<div class="kid-card" style="margin-top: 1rem; padding: 0.85rem;">
  <div style="display: flex; align-items: flex-start; gap: 0.6rem;">
    <div style="font-size: 1.5rem; color: #F59E0B; flex-shrink: 0;"><i class="fas fa-lightbulb"></i></div>
    <div style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.5;">
      <strong>🎮 Cara Main:</strong> Seret / Drag sampah ke tong yang tepat.
      Setiap jawaban benar dapat <strong style="color:#22C55E;">+100 × combo</strong>!
      Salah? Kombo hilang dan <strong style="color:#EF4444;">-50 poin</strong>.
      Total <strong>40+ misi unik</strong> — dijamin tidak terulang sebelum semua dimainkan!
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
