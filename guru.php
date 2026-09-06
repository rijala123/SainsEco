<?php
$pageTitle = "Panel Pengaturan Guru - Eco Clean App";
require_once __DIR__ . '/includes/header.php';
?>

<!-- Login Protection Container -->
<div id="guru-login-wrapper" class="kid-card" style="margin-top: 1rem; padding: 1.5rem; text-align: center;">
  <div style="font-size: 3.5rem; margin-bottom: 0.5rem;">👨‍🏫🔐</div>
  <h2 style="font-size: 1.4rem; color: var(--text-dark); margin-bottom: 0.3rem;">Area Khusus Guru</h2>
  <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Masukkan password khusus guru untuk mengelola halaman Peta, Misi Pilah, & Data Siswa.</p>

  <form id="form-guru-login" style="display: flex; flex-direction: column; gap: 0.75rem; max-width: 320px; margin: 0 auto;">
    <input type="password" id="input-guru-pass" class="kid-input" placeholder="Masukkan Password Guru (Default: guru123)" required style="text-align: center; font-size: 1rem; padding: 0.8rem;">
    <button type="submit" class="btn-kid btn-kid-green" style="padding: 0.8rem;">
      <i class="fas fa-key"></i> Masuk Panel Guru
    </button>
  </form>
</div>

<!-- Teacher Main Dashboard (Hidden until logged in) -->
<div id="guru-dashboard-wrapper" style="display: none;">
  <!-- Header Banner -->
  <div class="kid-banner" style="background: linear-gradient(135deg, #4F46E5, #3730A3); box-shadow: 0 6px 0 #1E1B4B;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
      <div>
        <h2>👨‍🏫 Panel Pengaturan Guru</h2>
        <p>Atur Halaman Peta, Item Misi Pilah, & Kelola Siswa</p>
      </div>
      <button type="button" id="btn-guru-logout" class="btn-kid" style="width: auto; padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.2); color: #FFF; font-size: 0.8rem;">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </button>
    </div>
  </div>

  <!-- Sub Navigation Tabs (4 Tabs) -->
  <div style="display: flex; gap: 0.35rem; margin-bottom: 1rem; overflow-x: auto;">
    <button type="button" class="btn-kid guru-tab-btn active" data-tab="tab-levels" style="flex: 1; padding: 0.65rem 0.3rem; font-size: 0.8rem; background: #4F46E5; color: #FFF; border-color: #3730A3;">
      <i class="fas fa-map"></i> Level Peta
    </button>
    <button type="button" class="btn-kid guru-tab-btn" data-tab="tab-waste" style="flex: 1; padding: 0.65rem 0.3rem; font-size: 0.8rem; background: #FFFFFF; color: var(--text-dark); border-color: #CBD5E1;">
      <i class="fas fa-dumpster"></i> Misi Pilah
    </button>
    <button type="button" class="btn-kid guru-tab-btn" data-tab="tab-students" style="flex: 1; padding: 0.65rem 0.3rem; font-size: 0.8rem; background: #FFFFFF; color: var(--text-dark); border-color: #CBD5E1;">
      <i class="fas fa-user-graduate"></i> Siswa
    </button>
    <button type="button" class="btn-kid guru-tab-btn" data-tab="tab-security" style="flex: 1; padding: 0.65rem 0.3rem; font-size: 0.8rem; background: #FFFFFF; color: var(--text-dark); border-color: #CBD5E1;">
      <i class="fas fa-lock"></i> Password
    </button>
  </div>

  <!-- TAB 1: PETA PETUALANGAN LEVELS -->
  <div id="tab-levels" class="guru-tab-content" style="display: block;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
      <h3 style="font-size: 1.1rem; color: var(--text-dark); font-weight: 800;">🗺️ Daftar Level Peta Petualangan</h3>
      <button type="button" class="btn-kid btn-kid-green" id="btn-add-level" style="width: auto; padding: 0.45rem 0.85rem; font-size: 0.8rem;">
        <i class="fas fa-plus-circle"></i> Tambah Level
      </button>
    </div>

    <div id="levels-list-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <!-- Loaded via JS -->
      <div style="text-align: center; padding: 1.5rem; color: var(--text-muted);">Memuat daftar level...</div>
    </div>
  </div>

  <!-- TAB 2: MISI PILAH SAMPAH -->
  <div id="tab-waste" class="guru-tab-content" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem;">
      <h3 style="font-size: 1.1rem; color: var(--text-dark); font-weight: 800;">🎮 Database Item Misi Pilah</h3>
      <button type="button" class="btn-kid btn-kid-green" id="btn-add-waste" style="width: auto; padding: 0.45rem 0.85rem; font-size: 0.8rem;">
        <i class="fas fa-plus-circle"></i> Tambah Sampah
      </button>
    </div>

    <div id="waste-list-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <!-- Loaded via JS -->
      <div style="text-align: center; padding: 1.5rem; color: var(--text-muted);">Memuat item sampah...</div>
    </div>
  </div>

  <!-- TAB 3: KELOLA SISWA -->
  <div id="tab-students" class="guru-tab-content" style="display: none;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
      <h3 style="font-size: 1.1rem; color: var(--text-dark); font-weight: 800; margin: 0;">🎓 Kelola Data Siswa & Kunci Akses</h3>
      <button type="button" class="btn-kid btn-kid-green" id="btn-add-student" style="width: auto; padding: 0.45rem 0.85rem; font-size: 0.8rem;">
        <i class="fas fa-user-plus"></i> Tambah Siswa Baru
      </button>
    </div>

    <div style="margin-bottom: 0.85rem;">
      <input type="text" id="input-search-student" class="kid-input" placeholder="🔍 Cari Siswa berdasarkan Nama, Kelas, atau Kunci Akses..." style="padding: 0.6rem 0.8rem; font-size: 0.85rem;">
    </div>

    <div id="students-list-container" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <!-- Loaded via JS -->
      <div style="text-align: center; padding: 1.5rem; color: var(--text-muted);">Memuat data siswa...</div>
    </div>
  </div>

  <!-- TAB 4: KEAMANAN & PASSWORD -->
  <div id="tab-security" class="guru-tab-content" style="display: none;">
    <div class="kid-card" style="padding: 1.25rem;">
      <h3 style="font-size: 1.1rem; color: var(--text-dark); font-weight: 800; margin-bottom: 0.5rem;">🔑 Ubah Password Guru</h3>
      <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">Perbarui password untuk menjaga keamanan panel kontrol guru.</p>
      
      <form id="form-change-password" style="display: flex; flex-direction: column; gap: 0.75rem;">
        <div>
          <label style="font-size: 0.8rem; font-weight: 800; color: var(--text-dark);">Password Lama:</label>
          <input type="password" id="input-old-pass" class="kid-input" required placeholder="Password lama">
        </div>
        <div>
          <label style="font-size: 0.8rem; font-weight: 800; color: var(--text-dark);">Password Baru:</label>
          <input type="password" id="input-new-pass" class="kid-input" required placeholder="Password baru">
        </div>
        <button type="submit" class="btn-kid btn-kid-blue" style="margin-top: 0.5rem; padding: 0.75rem;">
          <i class="fas fa-save"></i> Simpan Password Baru
        </button>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDITOR LEVEL -->
<div id="modal-level-editor" class="game-overlay-start" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 99; display: none; align-items: center; justify-content: center; padding: 1rem;">
  <div class="kid-card" style="width: 100%; max-width: 480px; max-height: 90vh; overflow-y: auto; background: #FFF; padding: 1.25rem; border-radius: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem;">
      <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-dark);" id="modal-level-title">Edit Level</h3>
      <button type="button" class="btn-close-modal" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
    </div>

    <form id="form-level-editor" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <input type="hidden" id="edit-level-id" value="0">
      
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.5rem;">
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Nomor Level:</label>
          <input type="number" id="edit-level-num" class="kid-input" min="1" required style="padding: 0.5rem;">
        </div>
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Emoji Icon:</label>
          <input type="text" id="edit-level-icon" class="kid-input" placeholder="🧩, 🌊, 🔍" required style="padding: 0.5rem;">
        </div>
      </div>

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Judul Level:</label>
        <input type="text" id="edit-level-title-input" class="kid-input" placeholder="Level 1: Pengenalan Pola" required style="padding: 0.5rem;">
      </div>

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Subjudul / Tema:</label>
        <input type="text" id="edit-level-subtitle" class="kid-input" placeholder="Cocokkan gambar masalah & penyebab" style="padding: 0.5rem;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Tipe Permainan:</label>
          <select id="edit-level-type" class="kid-input" style="padding: 0.5rem; font-size: 0.82rem;">
            <option value="matching">Pencocokan (Matching)</option>
            <option value="multichoice">Pilihan Ceklis (Multi-Choice)</option>
            <option value="filter">Filter Solusi (Filter)</option>
            <option value="sequence">Urutan Langkah (Sequence)</option>
            <option value="decision">Decision Game (Decision)</option>
          </select>
        </div>
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Warna Theme (Hex):</label>
          <input type="color" id="edit-level-color" class="kid-input" value="#22C55E" style="height: 38px; padding: 2px;">
        </div>
      </div>

      <!-- Path Gambar Ilustrasi with Direct File Upload -->
      <div>
        <label style="font-size: 0.78rem; font-weight: 800; display: block; margin-bottom: 0.2rem;">Path Gambar Ilustrasi:</label>
        <div style="display: flex; gap: 0.4rem; align-items: center;">
          <input type="text" id="edit-level-illustration" class="kid-input" placeholder="assets/images/level1_bg.png" style="padding: 0.5rem; flex: 1;">
          <input type="file" id="edit-level-file-input" accept="image/*" style="display: none;">
          <button type="button" class="btn-kid btn-kid-blue" id="btn-browse-image" style="width: auto; padding: 0.5rem 0.75rem; font-size: 0.75rem; white-space: nowrap;">
            <i class="fas fa-folder-open"></i> Upload File
          </button>
        </div>
        <div id="image-preview-container" style="display: none; align-items: center; gap: 0.5rem; background: #F8FAFC; border: 1.5px dashed #22C55E; border-radius: 12px; padding: 0.5rem; margin-top: 0.4rem;">
          <img id="img-preview-thumb" src="" alt="Preview" style="max-height: 75px; max-width: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #CBD5E1;">
          <div style="font-size: 0.75rem; color: #16A34A; font-weight: 800;">
            <i class="fas fa-check-circle"></i> Gambar berhasil dimuat!
          </div>
        </div>
      </div>

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Petunjuk Soal / Instruksi:</label>
        <textarea id="edit-level-instructions" class="kid-input" rows="2" placeholder="Petunjuk pengerjaan soal..." style="padding: 0.5rem; font-size: 0.82rem;"></textarea>
      </div>

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Konten Soal (JSON Format):</label>
        <textarea id="edit-level-json" class="kid-input" rows="7" required style="padding: 0.5rem; font-family: monospace; font-size: 0.75rem;"></textarea>
        <div style="font-size: 0.68rem; color: var(--text-muted); margin-top: 0.2rem;">* Format JSON otomatis disesuaikan berdasarkan Tipe Permainan.</div>
      </div>

      <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
        <button type="button" class="btn-kid btn-close-modal" style="flex: 1; background: #E2E8F0; color: var(--text-dark); padding: 0.65rem;">Batal</button>
        <button type="submit" class="btn-kid btn-kid-green" style="flex: 1; padding: 0.65rem;">Simpan Level</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDITOR WASTE ITEM -->
<div id="modal-waste-editor" class="game-overlay-start" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 99; display: none; align-items: center; justify-content: center; padding: 1rem;">
  <div class="kid-card" style="width: 100%; max-width: 400px; background: #FFF; padding: 1.25rem; border-radius: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem;">
      <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-dark);" id="modal-waste-title">Edit Item Sampah</h3>
      <button type="button" class="btn-close-modal" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
    </div>

    <form id="form-waste-editor" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <input type="hidden" id="edit-waste-id" value="0">

      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 0.5rem;">
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Emoji Icon:</label>
          <input type="text" id="edit-waste-icon" class="kid-input" placeholder="🍎, 🍾, 🔋" required style="padding: 0.5rem; text-align: center;">
        </div>
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Nama Sampah:</label>
          <input type="text" id="edit-waste-name" class="kid-input" placeholder="Sisa Apel" required style="padding: 0.5rem;">
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 0.5rem;">
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Kategori Sampah:</label>
          <select id="edit-waste-category" class="kid-input" style="padding: 0.5rem; font-size: 0.82rem;">
            <option value="organik">🍃 Organik</option>
            <option value="anorganik">🥤 Anorganik</option>
            <option value="b3">🔋 B3 (Berbahaya)</option>
          </select>
        </div>
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Poin Skor:</label>
          <input type="number" id="edit-waste-points" class="kid-input" value="10" min="5" style="padding: 0.5rem;">
        </div>
      </div>

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Fakta Edukasi / Deskripsi:</label>
        <textarea id="edit-waste-fact" class="kid-input" rows="2" placeholder="Fakta unik edukasi sampah ini..." style="padding: 0.5rem; font-size: 0.82rem;"></textarea>
      </div>

      <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
        <button type="button" class="btn-kid btn-close-modal" style="flex: 1; background: #E2E8F0; color: var(--text-dark); padding: 0.65rem;">Batal</button>
        <button type="submit" class="btn-kid btn-kid-green" style="flex: 1; padding: 0.65rem;">Simpan Item</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EDITOR SISWA -->
<div id="modal-student-editor" class="game-overlay-start" style="position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 99; display: none; align-items: center; justify-content: center; padding: 1rem;">
  <div class="kid-card" style="width: 100%; max-width: 400px; background: #FFF; padding: 1.25rem; border-radius: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 2px solid #E2E8F0; padding-bottom: 0.5rem;">
      <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-dark);" id="modal-student-title">Edit Data Siswa</h3>
      <button type="button" class="btn-close-modal" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
    </div>

    <form id="form-student-editor" style="display: flex; flex-direction: column; gap: 0.75rem;">
      <input type="hidden" id="edit-student-id" value="0">

      <div>
        <label style="font-size: 0.78rem; font-weight: 800;">Nama Lengkap Siswa:</label>
        <input type="text" id="edit-student-name" class="kid-input" placeholder="Contoh: Budi Santoso" required style="padding: 0.55rem;">
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Kelas:</label>
          <input type="text" id="edit-student-class" class="kid-input" placeholder="Kelas 5 Eco" required style="padding: 0.55rem;">
        </div>
        <div>
          <label style="font-size: 0.78rem; font-weight: 800;">Kunci Akses (PIN):</label>
          <input type="text" id="edit-student-key" class="kid-input" placeholder="ECO-1234" required style="padding: 0.55rem; text-transform: uppercase; font-family: monospace; font-weight: 800;">
        </div>
      </div>

      <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
        <button type="button" class="btn-kid btn-close-modal" style="flex: 1; background: #E2E8F0; color: var(--text-dark); padding: 0.65rem;">Batal</button>
        <button type="submit" class="btn-kid btn-kid-green" style="flex: 1; padding: 0.65rem;">Simpan Siswa</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const loginWrapper = document.getElementById('guru-login-wrapper');
  const dashboardWrapper = document.getElementById('guru-dashboard-wrapper');

  // Check login state from sessionStorage
  if (sessionStorage.getItem('guru_logged_in') === 'true') {
    loginWrapper.style.display = 'none';
    dashboardWrapper.style.display = 'block';
    loadLevelsList();
    loadWasteList();
    loadStudentsList();
  }

  // Handle Login
  document.getElementById('form-guru-login').addEventListener('submit', (e) => {
    e.preventDefault();
    const pass = document.getElementById('input-guru-pass').value.trim();
    if (!pass) return;

    fetch('api/index.php?action=guru_login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password: pass })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast('Login Guru Berhasil!', 'success');
        sessionStorage.setItem('guru_logged_in', 'true');
        loginWrapper.style.display = 'none';
        dashboardWrapper.style.display = 'block';
        loadLevelsList();
        loadWasteList();
        loadStudentsList();
      } else {
        showToast(res.message || 'Password Guru salah!', 'error');
      }
    })
    .catch(err => console.error(err));
  });

  // Handle Logout
  document.getElementById('btn-guru-logout').addEventListener('click', () => {
    sessionStorage.removeItem('guru_logged_in');
    dashboardWrapper.style.display = 'none';
    loginWrapper.style.display = 'block';
    showToast('Berhasil keluar dari Panel Guru', 'info');
  });

  // Tab Switching
  document.querySelectorAll('.guru-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetTab = btn.getAttribute('data-tab');
      document.querySelectorAll('.guru-tab-btn').forEach(b => {
        b.classList.remove('active');
        b.style.background = '#FFFFFF';
        b.style.color = 'var(--text-dark)';
        b.style.borderColor = '#CBD5E1';
      });
      btn.classList.add('active');
      btn.style.background = '#4F46E5';
      btn.style.color = '#FFF';
      btn.style.borderColor = '#3730A3';

      document.querySelectorAll('.guru-tab-content').forEach(c => c.style.display = 'none');
      document.getElementById(targetTab).style.display = 'block';
    });
  });

  // Close modals
  document.querySelectorAll('.btn-close-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('modal-level-editor').style.display = 'none';
      document.getElementById('modal-waste-editor').style.display = 'none';
      document.getElementById('modal-student-editor').style.display = 'none';
    });
  });

  // ==========================================
  // FILE UPLOAD FOR LEVEL ILLUSTRATIONS
  // ==========================================
  const btnBrowse = document.getElementById('btn-browse-image');
  const fileInput = document.getElementById('edit-level-file-input');
  const illInput = document.getElementById('edit-level-illustration');
  const previewBox = document.getElementById('image-preview-container');
  const previewImg = document.getElementById('img-preview-thumb');

  btnBrowse.addEventListener('click', () => {
    fileInput.click();
  });

  fileInput.addEventListener('change', () => {
    if (fileInput.files && fileInput.files[0]) {
      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('image_file', file);

      showToast('Mengunggah gambar ke server...', 'info');

      fetch('api/index.php?action=upload_image', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(res => {
        if (res.success && res.file_path) {
          illInput.value = res.file_path;
          previewImg.src = res.file_path + '?v=' + Date.now();
          previewBox.style.display = 'flex';
          showToast('Gambar berhasil diunggah & dipasang!', 'success');
        } else {
          showToast(res.message || 'Gagal mengunggah gambar', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        showToast('Gagal terhubung ke server upload', 'error');
      });
    }
  });

  illInput.addEventListener('input', () => {
    const val = illInput.value.trim();
    if (val) {
      previewImg.src = val;
      previewBox.style.display = 'flex';
    } else {
      previewBox.style.display = 'none';
    }
  });

  // ==========================================
  // TAB 1: LEVELS MANAGEMENT
  // ==========================================
  let levelsData = [];

  function loadLevelsList() {
    fetch('api/index.php?action=get_levels')
      .then(res => res.json())
      .then(res => {
        if (res.success && res.data) {
          levelsData = res.data;
          renderLevelsList();
        }
      });
  }

  function renderLevelsList() {
    const container = document.getElementById('levels-list-container');
    if (levelsData.length === 0) {
      container.innerHTML = '<div class="kid-card" style="text-align:center;">Belum ada level. Klik tombol "Tambah Level" di atas.</div>';
      return;
    }

    container.innerHTML = levelsData.map(lvl => `
      <div class="kid-card" style="display: flex; align-items: center; justify-content: space-between; border-left: 6px solid ${lvl.bg_color || '#22C55E'}; padding: 0.85rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div style="font-size: 2rem;">${lvl.icon || '🧩'}</div>
          <div>
            <div style="font-size: 0.72rem; font-weight: 800; color: #4F46E5;">LEVEL ${lvl.level_number} • Tipe: ${lvl.game_type}</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-dark);">${escapeHtml(lvl.title)}</div>
            <div style="font-size: 0.78rem; color: var(--text-muted);">${escapeHtml(lvl.subtitle || '')}</div>
          </div>
        </div>
        <div style="display: flex; gap: 0.35rem;">
          <button type="button" class="btn-kid btn-edit-level" data-id="${lvl.id}" style="width: auto; padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #FEF3C7; color: #B45309; border-color: #F59E0B;">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button type="button" class="btn-kid btn-del-level" data-id="${lvl.id}" style="width: auto; padding: 0.4rem 0.6rem; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border-color: #EF4444;">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `).join('');

    // Attach Edit & Delete Listeners
    container.querySelectorAll('.btn-edit-level').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        openLevelEditor(id);
      });
    });

    container.querySelectorAll('.btn-del-level').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        if (confirm('Yakin ingin menghapus level ini?')) {
          deleteLevel(id);
        }
      });
    });
  }

  document.getElementById('btn-add-level').addEventListener('click', () => {
    openLevelEditor(0);
  });

  function openLevelEditor(id) {
    const modal = document.getElementById('modal-level-editor');
    document.getElementById('modal-level-title').textContent = id > 0 ? '✏️ Edit Level Peta' : '➕ Tambah Level Baru';
    document.getElementById('edit-level-id').value = id;

    if (id > 0) {
      fetch(`api/index.php?action=get_level_detail&level_number=${id}`)
        .then(res => res.json())
        .then(res => {
          if (res.success && res.data) {
            const d = res.data;
            document.getElementById('edit-level-num').value = d.level_number;
            document.getElementById('edit-level-icon').value = d.icon;
            document.getElementById('edit-level-title-input').value = d.title;
            document.getElementById('edit-level-subtitle').value = d.subtitle || '';
            document.getElementById('edit-level-type').value = d.game_type;
            document.getElementById('edit-level-color').value = d.bg_color || '#22C55E';
            document.getElementById('edit-level-illustration').value = d.illustration || '';
            document.getElementById('edit-level-instructions').value = d.instructions || '';
            document.getElementById('edit-level-json').value = d.content_json || JSON.stringify(d.content, null, 2);

            if (d.illustration) {
              previewImg.src = d.illustration;
              previewBox.style.display = 'flex';
            } else {
              previewBox.style.display = 'none';
            }
            modal.style.display = 'flex';
          }
        });
    } else {
      const nextNum = levelsData.length > 0 ? Math.max(...levelsData.map(l => l.level_number)) + 1 : 1;
      document.getElementById('edit-level-num').value = nextNum;
      document.getElementById('edit-level-icon').value = '🧩';
      document.getElementById('edit-level-title-input').value = `Level ${nextNum}: Judul Level Baru`;
      document.getElementById('edit-level-subtitle').value = 'Deskripsi level baru';
      document.getElementById('edit-level-type').value = 'multichoice';
      document.getElementById('edit-level-color').value = '#22C55E';
      document.getElementById('edit-level-illustration').value = '';
      document.getElementById('edit-level-instructions').value = 'Petunjuk pengerjaan soal...';
      document.getElementById('edit-level-json').value = JSON.stringify({
        questions: [{
          id: 1,
          topic: "SOAL 1:",
          question: "Pertanyaan baru...",
          options: [
            { id: "1", text: "Pilihan A", correct: true },
            { id: "2", text: "Pilihan B", correct: false }
          ],
          feedback_correct: "🎉 Jawaban Benar!",
          feedback_wrong: "⚠️ Jawaban Salah."
        }]
      }, null, 2);
      previewBox.style.display = 'none';
      modal.style.display = 'flex';
    }
  }

  document.getElementById('form-level-editor').addEventListener('submit', (e) => {
    e.preventDefault();
    const id = parseInt(document.getElementById('edit-level-id').value);
    const levelNum = parseInt(document.getElementById('edit-level-num').value);
    const icon = document.getElementById('edit-level-icon').value.trim();
    const title = document.getElementById('edit-level-title-input').value.trim();
    const subtitle = document.getElementById('edit-level-subtitle').value.trim();
    const gameType = document.getElementById('edit-level-type').value;
    const bgColor = document.getElementById('edit-level-color').value;
    const illustration = document.getElementById('edit-level-illustration').value.trim();
    const instructions = document.getElementById('edit-level-instructions').value.trim();
    const jsonStr = document.getElementById('edit-level-json').value.trim();

    try {
      JSON.parse(jsonStr);
    } catch (err) {
      showToast('Format JSON tidak valid! Periksa koma dan tanda kutip.', 'error');
      return;
    }

    fetch('api/index.php?action=save_level_admin', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: id,
        level_number: levelNum,
        title: title,
        subtitle: subtitle,
        game_type: gameType,
        icon: icon,
        bg_color: bgColor,
        illustration: illustration,
        instructions: instructions,
        content: jsonStr
      })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast(res.message, 'success');
        document.getElementById('modal-level-editor').style.display = 'none';
        loadLevelsList();
      } else {
        showToast(res.message || 'Gagal menyimpan level', 'error');
      }
    });
  });

  function deleteLevel(id) {
    fetch('api/index.php?action=delete_level_admin', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast('Level berhasil dihapus', 'success');
        loadLevelsList();
      }
    });
  }

  // ==========================================
  // TAB 2: WASTE ITEMS MANAGEMENT
  // ==========================================
  let wasteData = [];

  function loadWasteList() {
    fetch('api/index.php?action=get_waste_items')
      .then(res => res.json())
      .then(res => {
        if (res.success && res.data) {
          wasteData = res.data;
          renderWasteList();
        }
      });
  }

  function renderWasteList() {
    const container = document.getElementById('waste-list-container');
    if (wasteData.length === 0) {
      container.innerHTML = '<div class="kid-card" style="text-align:center;">Belum ada item sampah.</div>';
      return;
    }

    const catBadgeMap = {
      'organik': '<span style="background: #DCFCE7; color: #15803D; padding: 0.2rem 0.5rem; border-radius: 8px; font-weight: 800; font-size: 0.7rem;">🍃 Organik</span>',
      'anorganik': '<span style="background: #FEF3C7; color: #B45309; padding: 0.2rem 0.5rem; border-radius: 8px; font-weight: 800; font-size: 0.7rem;">🥤 Anorganik</span>',
      'b3': '<span style="background: #FEE2E2; color: #B91C1C; padding: 0.2rem 0.5rem; border-radius: 8px; font-weight: 800; font-size: 0.7rem;">🔋 B3 Berbahaya</span>'
    };

    container.innerHTML = wasteData.map(w => `
      <div class="kid-card" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <div style="font-size: 1.8rem;">${w.icon || '🗑️'}</div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.4rem;">
              <strong style="font-size: 0.9rem; color: var(--text-dark);">${escapeHtml(w.name)}</strong>
              ${catBadgeMap[w.category] || w.category}
            </div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">${escapeHtml(w.fact || '')} (${w.points} Poin)</div>
          </div>
        </div>
        <div style="display: flex; gap: 0.35rem;">
          <button type="button" class="btn-kid btn-edit-waste" data-id="${w.id}" style="width: auto; padding: 0.35rem 0.55rem; font-size: 0.75rem; background: #FEF3C7; color: #B45309; border-color: #F59E0B;">
            <i class="fas fa-edit"></i>
          </button>
          <button type="button" class="btn-kid btn-del-waste" data-id="${w.id}" style="width: auto; padding: 0.35rem 0.55rem; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border-color: #EF4444;">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    `).join('');

    container.querySelectorAll('.btn-edit-waste').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        openWasteEditor(id);
      });
    });

    container.querySelectorAll('.btn-del-waste').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        if (confirm('Hapus item sampah ini?')) {
          deleteWaste(id);
        }
      });
    });
  }

  document.getElementById('btn-add-waste').addEventListener('click', () => openWasteEditor(0));

  function openWasteEditor(id) {
    const modal = document.getElementById('modal-waste-editor');
    document.getElementById('modal-waste-title').textContent = id > 0 ? '✏️ Edit Item Sampah' : '➕ Tambah Sampah Baru';
    document.getElementById('edit-waste-id').value = id;

    if (id > 0) {
      const item = wasteData.find(w => w.id === id);
      if (item) {
        document.getElementById('edit-waste-icon').value = item.icon;
        document.getElementById('edit-waste-name').value = item.name;
        document.getElementById('edit-waste-category').value = item.category;
        document.getElementById('edit-waste-points').value = item.points;
        document.getElementById('edit-waste-fact').value = item.fact || '';
        modal.style.display = 'flex';
      }
    } else {
      document.getElementById('edit-waste-icon').value = '🍎';
      document.getElementById('edit-waste-name').value = '';
      document.getElementById('edit-waste-category').value = 'organik';
      document.getElementById('edit-waste-points').value = 10;
      document.getElementById('edit-waste-fact').value = '';
      modal.style.display = 'flex';
    }
  }

  document.getElementById('form-waste-editor').addEventListener('submit', (e) => {
    e.preventDefault();
    const id = parseInt(document.getElementById('edit-waste-id').value);
    const icon = document.getElementById('edit-waste-icon').value.trim();
    const name = document.getElementById('edit-waste-name').value.trim();
    const category = document.getElementById('edit-waste-category').value;
    const points = parseInt(document.getElementById('edit-waste-points').value);
    const fact = document.getElementById('edit-waste-fact').value.trim();

    fetch('api/index.php?action=save_waste_item', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: id,
        icon: icon,
        name: name,
        category: category,
        points: points,
        fact: fact
      })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast(res.message, 'success');
        document.getElementById('modal-waste-editor').style.display = 'none';
        loadWasteList();
      }
    });
  });

  function deleteWaste(id) {
    fetch('api/index.php?action=delete_waste_item', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast('Item sampah berhasil dihapus', 'success');
        loadWasteList();
      }
    });
  }

  // ==========================================
  // TAB 3: STUDENT MANAGEMENT (STUDENTS)
  // ==========================================
  let studentsData = [];

  function loadStudentsList(search = '') {
    fetch(`api/index.php?action=get_students&search=${encodeURIComponent(search)}`)
      .then(res => res.json())
      .then(res => {
        if (res.success && res.data) {
          studentsData = res.data;
          renderStudentsList();
        }
      });
  }

  function renderStudentsList() {
    const container = document.getElementById('students-list-container');
    if (studentsData.length === 0) {
      container.innerHTML = '<div class="kid-card" style="text-align:center;">Belum ada data siswa terdaftar.</div>';
      return;
    }

    container.innerHTML = studentsData.map(s => `
      <div class="kid-card" style="display: flex; align-items: center; justify-content: space-between; padding: 0.8rem; border-left: 5px solid #0284C7;">
        <div>
          <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.2rem;">
            <strong style="font-size: 0.95rem; color: var(--text-dark);">${escapeHtml(s.student_name)}</strong>
            <span style="background: #E0F2FE; color: #0369A1; padding: 0.15rem 0.45rem; border-radius: 8px; font-weight: 800; font-size: 0.7rem;">${escapeHtml(s.school_class)}</span>
          </div>
          <div style="font-size: 0.78rem; font-family: monospace; font-weight: 800; color: #16A34A;">
            🔑 PIN: ${escapeHtml(s.key_code)}
          </div>
          <div style="font-size: 0.7rem; color: var(--text-muted);">
            Terakhir Aktif: ${s.last_active ? s.last_active : 'Belum pernah'}
          </div>
        </div>
        <div style="display: flex; gap: 0.35rem;">
          <button type="button" class="btn-kid btn-edit-student" data-id="${s.id}" style="width: auto; padding: 0.4rem 0.65rem; font-size: 0.75rem; background: #FEF3C7; color: #B45309; border-color: #F59E0B;">
            <i class="fas fa-user-edit"></i> Edit
          </button>
          <button type="button" class="btn-kid btn-del-student" data-id="${s.id}" data-name="${escapeHtml(s.student_name)}" style="width: auto; padding: 0.4rem 0.65rem; font-size: 0.75rem; background: #FEE2E2; color: #991B1B; border-color: #EF4444;">
            <i class="fas fa-trash-alt"></i> Hapus
          </button>
        </div>
      </div>
    `).join('');

    container.querySelectorAll('.btn-edit-student').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        openStudentEditor(id);
      });
    });

    container.querySelectorAll('.btn-del-student').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = parseInt(btn.getAttribute('data-id'));
        const name = btn.getAttribute('data-name');
        if (confirm(`Yakin ingin menghapus siswa "${name}" beserta seluruh riwayat skornya?`)) {
          deleteStudent(id);
        }
      });
    });
  }

  document.getElementById('input-search-student').addEventListener('input', (e) => {
    loadStudentsList(e.target.value.trim());
  });

  document.getElementById('btn-add-student').addEventListener('click', () => openStudentEditor(0));

  function openStudentEditor(id) {
    const modal = document.getElementById('modal-student-editor');
    document.getElementById('modal-student-title').textContent = id > 0 ? '✏️ Edit Data Siswa' : '➕ Tambah Siswa Baru';
    document.getElementById('edit-student-id').value = id;

    if (id > 0) {
      const s = studentsData.find(st => st.id === id);
      if (s) {
        document.getElementById('edit-student-name').value = s.student_name;
        document.getElementById('edit-student-class').value = s.school_class;
        document.getElementById('edit-student-key').value = s.key_code;
        modal.style.display = 'flex';
      }
    } else {
      const randCode = 'ECO-' + Math.random().toString(36).substring(2, 6).toUpperCase();
      document.getElementById('edit-student-name').value = '';
      document.getElementById('edit-student-class').value = 'Kelas 5 Eco';
      document.getElementById('edit-student-key').value = randCode;
      modal.style.display = 'flex';
    }
  }

  document.getElementById('form-student-editor').addEventListener('submit', (e) => {
    e.preventDefault();
    const id = parseInt(document.getElementById('edit-student-id').value);
    const name = document.getElementById('edit-student-name').value.trim();
    const cls = document.getElementById('edit-student-class').value.trim();
    const key = document.getElementById('edit-student-key').value.trim();

    fetch('api/index.php?action=save_student', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        id: id,
        student_name: name,
        school_class: cls,
        key_code: key
      })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast(res.message, 'success');
        document.getElementById('modal-student-editor').style.display = 'none';
        loadStudentsList();
      } else {
        showToast(res.message || 'Gagal menyimpan siswa', 'error');
      }
    });
  });

  function deleteStudent(id) {
    fetch('api/index.php?action=delete_student', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast('Data Siswa berhasil dihapus', 'success');
        loadStudentsList();
      }
    });
  }

  // ==========================================
  // TAB 4: SECURITY / CHANGE PASSWORD
  // ==========================================
  document.getElementById('form-change-password').addEventListener('submit', (e) => {
    e.preventDefault();
    const oldPass = document.getElementById('input-old-pass').value.trim();
    const newPass = document.getElementById('input-new-pass').value.trim();

    fetch('api/index.php?action=change_guru_password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ old_password: oldPass, new_password: newPass })
    })
    .then(res => res.json())
    .then(res => {
      if (res.success) {
        showToast(res.message, 'success');
        document.getElementById('input-old-pass').value = '';
        document.getElementById('input-new-pass').value = '';
      } else {
        showToast(res.message || 'Gagal mengubah password', 'error');
      }
    });
  });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
