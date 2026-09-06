/**
 * Enhanced Eco Clean Game - Main Interactivity & Utility Script
 */

const ECO_CONFIG = {
  apiUrl: 'api/index.php',
  storageKey: 'eco_clean_user_data'
};

// Global User State
let currentUser = {
  key_code: '',
  student_name: 'Pahlawan Eco',
  school_class: 'Kelas 5 Eco'
};

// Audio Synthesizer via Web Audio API (Zero external assets required!)
class SoundSynth {
  constructor() {
    this.ctx = null;
    this.muted = false;
  }

  init() {
    if (!this.ctx) {
      const AudioCtx = window.AudioContext || window.webkitAudioContext;
      if (AudioCtx) this.ctx = new AudioCtx();
    }
    if (this.ctx && this.ctx.state === 'suspended') {
      this.ctx.resume();
    }
  }

  playCorrect() {
    if (this.muted) return;
    this.init();
    if (!this.ctx) return;

    const osc = this.ctx.createOscillator();
    const gain = this.ctx.createGain();
    osc.type = 'sine';
    
    // Quick ascending chime: C5 -> E5 -> G5
    const now = this.ctx.currentTime;
    osc.frequency.setValueAtTime(523.25, now);
    osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.08);
    osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.16);

    gain.gain.setValueAtTime(0.3, now);
    gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);

    osc.connect(gain);
    gain.connect(this.ctx.destination);

    osc.start(now);
    osc.stop(now + 0.3);
  }

  playWrong() {
    if (this.muted) return;
    this.init();
    if (!this.ctx) return;

    const osc = this.ctx.createOscillator();
    const gain = this.ctx.createGain();
    osc.type = 'sawtooth';

    const now = this.ctx.currentTime;
    osc.frequency.setValueAtTime(220, now);
    osc.frequency.exponentialRampToValueAtTime(130, now + 0.25);

    gain.gain.setValueAtTime(0.3, now);
    gain.gain.exponentialRampToValueAtTime(0.01, now + 0.25);

    osc.connect(gain);
    gain.connect(this.ctx.destination);

    osc.start(now);
    osc.stop(now + 0.25);
  }

  playLevelUp() {
    if (this.muted) return;
    this.init();
    if (!this.ctx) return;

    const notes = [523.25, 659.25, 783.99, 1046.50];
    const now = this.ctx.currentTime;
    notes.forEach((freq, idx) => {
      const osc = this.ctx.createOscillator();
      const gain = this.ctx.createGain();
      osc.type = 'triangle';
      osc.frequency.value = freq;
      gain.gain.setValueAtTime(0.2, now + idx * 0.08);
      gain.gain.exponentialRampToValueAtTime(0.01, now + idx * 0.08 + 0.2);
      osc.connect(gain);
      gain.connect(this.ctx.destination);
      osc.start(now + idx * 0.08);
      osc.stop(now + idx * 0.08 + 0.2);
    });
  }
}

const ecoSound = new SoundSynth();

// Load stored user data
function loadUserData() {
  const saved = localStorage.getItem(ECO_CONFIG.storageKey);
  if (saved) {
    try {
      currentUser = JSON.parse(saved);
    } catch (e) {
      console.warn('Failed to parse stored user data');
    }
  }

  // Ensure persistent guest key code if none exists
  if (!currentUser.key_code) {
    const randomGuest = 'ECO-GUEST-' + Math.floor(1000 + Math.random() * 9000);
    currentUser.key_code = randomGuest;
    localStorage.setItem(ECO_CONFIG.storageKey, JSON.stringify(currentUser));
  }

  updateUserBadgeUI();
}

// Save user data
function saveUserData(data) {
  currentUser = { ...currentUser, ...data };
  localStorage.setItem(ECO_CONFIG.storageKey, JSON.stringify(currentUser));
  updateUserBadgeUI();
}

// Update Header User Badge
function updateUserBadgeUI() {
  const badgeEl = document.getElementById('user-badge-display');
  if (badgeEl) {
    if (currentUser.key_code && !currentUser.key_code.startsWith('ECO-GUEST-')) {
      badgeEl.innerHTML = `
        <span class="status-dot"></span>
        <span>${escapeHtml(currentUser.student_name)}</span>
      `;
    } else {
      badgeEl.innerHTML = `
        <span class="status-dot"></span>
        <span>${escapeHtml(currentUser.student_name)}</span>
      `;
    }
  }
}

// Toast Notification System
function showToast(message, type = 'info') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  let icon = 'fa-info-circle';
  if (type === 'success') icon = 'fa-check-circle';
  if (type === 'warning') icon = 'fa-exclamation-triangle';
  if (type === 'error') icon = 'fa-times-circle';

  toast.innerHTML = `<i class="fas ${icon}"></i> <span>${escapeHtml(message)}</span>`;
  container.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

// HTML Escaping
function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
  loadUserData();
});
