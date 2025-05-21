// Countdown Timer
function updateCountdown() {
    const countdown = document.getElementById('countdown-timer');
    if (!countdown) return;

    const targetDate = countdown.dataset.target;
    if (!targetDate) return;

    // Parse tanggal dengan timezone lokal browser
    const now = new Date();
    const target = new Date(targetDate);
    
    // Hitung selisih dalam detik
    const diff = (target - now) / 1000;
    
    // Jika waktu sudah lewat
    if (diff <= 0) {
        countdown.innerHTML = '<div class="time-up">PENGUMUMAN TELAH DIMULAI!</div>';
        return;
    }
    
    // Hitung komponen waktu
    const days = Math.floor(diff / (60 * 60 * 24));
    const hours = Math.floor((diff % (60 * 60 * 24)) / (60 * 60));
    const minutes = Math.floor((diff % (60 * 60)) / 60);
    const seconds = Math.floor(diff % 60);
    
    // Update tampilan
    document.getElementById('days').textContent = days.toString().padStart(2, '0');
    document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
    document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
    document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
}

// Admin Modal
function setupAdminModal() {
  const modal = document.getElementById("studentModal");
  if (!modal) return;

  const addBtn = document.getElementById("addStudentBtn");
  const closeBtn = modal.querySelector(".close");
  const modalTitle = document.getElementById("modalTitle");
  const submitBtn = document.getElementById("submitBtn");

  addBtn.addEventListener("click", () => {
    modal.style.display = "block";
    modalTitle.textContent = "Tambah Siswa Baru";
    submitBtn.name = "add";
    document.getElementById("studentId").value = "";
    document.getElementById("nisn").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("kelas").value = "";
    document.getElementById("absen").value = "";
    document.getElementById("status").value = "Lulus";
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
}

// Edit Student Function
function editStudent(id, nisn, nama, kelas, absen, status) {
  const modal = document.getElementById("studentModal");
  modal.style.display = "block";

  document.getElementById("modalTitle").textContent = "Edit Data Siswa";
  document.getElementById("submitBtn").name = "edit";
  document.getElementById("studentId").value = id;
  document.getElementById("nisn").value = nisn;
  document.getElementById("nama").value = nama;
  document.getElementById("kelas").value = kelas;
  document.getElementById("absen").value = absen;
  document.getElementById("status").value = status;
}

// Logo Preview
function setupLogoPreview() {
  const logoInput = document.getElementById("logo");
  if (!logoInput) return;

  const logoPreview = document.getElementById("logoPreview");

  logoInput.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        logoPreview.src = event.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
}

// Initialize all functions when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  updateCountdown();
  setInterval(updateCountdown, 1000);

  setupAdminModal();
  setupLogoPreview();
});
