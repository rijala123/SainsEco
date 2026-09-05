<?php
$pageTitle = "Mulai - SainsEco";
$hideNav = true; // Sembunyikan header dan footer navigation
require_once __DIR__ . '/includes/header.php';
?>

<!-- Main Login/Start Card -->
<div class="kid-card" style="text-align: center; padding: 2rem 1.5rem; border-color: #22C55E; max-width: 400px; margin: 1rem auto;">
  
  <!-- Mascot / Avatar -->
  <div style="width: 80px; height: 80px; background: #22C55E; border: 3px solid #DCFCE7; border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; overflow: hidden; box-shadow: 0 4px 10px rgba(34, 197, 94, 0.3);">
    🦸‍♂️
  </div>

  <!-- Title & Subtitle -->
  <h1 style="font-size: 2rem; color: #15803D; margin-bottom: 0.2rem; font-weight: 900;">SainsEco</h1>
  <p style="font-size: 0.95rem; font-weight: 700; color: #22C55E; margin-bottom: 0.8rem;">Game Edukasi Berbasis Computational Thinking</p>
  
  <!-- Author Info -->
  <p style="font-size: 0.75rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 1.5rem;">
    Oleh: Sigit Waskito Adi, S.Pd<br>
    SDN 02 Karanganyar
  </p>

  <!-- Start Form -->
  <form id="form-generate-key" style="text-align: left;">
    <div style="margin-bottom: 1rem;">
      <div style="position: relative;">
        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-size: 1.1rem; color: #F97316;">✏️</span>
        <input type="text" id="input-student-name" class="kid-form-control" placeholder="Nama Siswa" required style="padding-left: 2.8rem; height: 3.2rem; font-size: 0.95rem; border-color: #A7F3D0; background: #FFFFFF;">
      </div>
    </div>

    <div style="margin-bottom: 1.5rem;">
      <div style="position: relative;">
        <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-size: 1.1rem; color: #8B5CF6;">🏫</span>
        <input type="text" id="input-school-class" class="kid-form-control" placeholder="Kelas (misal: 5A)" required style="padding-left: 2.8rem; height: 3.2rem; font-size: 0.95rem; border-color: #A7F3D0; background: #FFFFFF;">
      </div>
    </div>

    <button type="submit" class="btn-kid btn-kid-green" style="width: 100%; height: 3.5rem; font-size: 1.1rem;">
      🌱 Mulai Petualangan!
    </button>
  </form>

  <p style="font-size: 0.7rem; color: #94A3B8; margin-top: 1rem;">
    Selamatkan lingkungan bersama Eco Hero!
  </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const formGen = document.getElementById('form-generate-key');
  if (formGen) {
    formGen.addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('input-student-name').value.trim();
      const schoolClass = document.getElementById('input-school-class').value.trim();

      // Buat token secara background
      fetch('api/index.php?action=generate_key', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_name: name, school_class: schoolClass })
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          saveUserData(res.data);
          showToast(`Selamat datang, ${res.data.student_name}!`, 'success');
          // Langsung ke peta petualangan
          setTimeout(() => window.location.href = 'map.php', 1000);
        } else {
          showToast(res.message, 'error');
        }
      });
    });
  }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
