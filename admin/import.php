<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$importResult = null;
$importErrors = [];
$showSuccessAlert = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    // Validasi file
    if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $importErrors[] = "Error upload file. Kode error: " . $_FILES['csv_file']['error'];
    } else {
        $file = $_FILES['csv_file']['tmp_name'];
        
        // Fungsi untuk membaca CSV dengan berbagai delimiter
        function parseCSV($file) {
            $csvData = [];
            
            // Baca file untuk deteksi delimiter
            $firstLine = file_get_contents($file, false, null, 0, 1000);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            
            // Hapus BOM jika ada
            $bom = pack('H*','EFBBBF');
            $firstLine = preg_replace("/^$bom/", '', $firstLine);
            
            // Buka file
            if (($handle = fopen($file, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    // Skip baris kosong
                    if (count(array_filter($data, 'strlen')) > 0) {
                        // Hapus BOM dari kolom pertama jika ada
                        if (isset($data[0])) {
                            $data[0] = preg_replace("/^$bom/", '', $data[0]);
                        }
                        $csvData[] = $data;
                    }
                }
                fclose($handle);
            }
            return $csvData;
        }

        $csvData = parseCSV($file);
        
        if (empty($csvData)) {
            $importErrors[] = "File CSV kosong atau format tidak valid";
        } else {
            // Siapkan laporan import
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($csvData as $i => $row) {
                // Skip header jika ada
                if ($i === 0 && (strtolower($row[0]) === 'nisn' || strtolower($row[0]) === 'nisn')) {
                    continue;
                }

                // Pastikan jumlah kolom cukup
                if (count($row) < 5) {
                    $errors[] = "Baris " . ($i+1) . ": Format data tidak lengkap";
                    $errorCount++;
                    continue;
                }

                // Ambil data
                $nisn = trim($row[0]);
                $nama = trim($row[1]);
                $kelas = trim($row[2]);
                $absen = (int)trim($row[3]);
                $status = isset($row[4]) ? trim($row[4]) : 'Lulus';

                // Validasi NISN
                if (empty($nisn)) {
                    $errors[] = "Baris " . ($i+1) . ": NISN tidak boleh kosong";
                    $errorCount++;
                    continue;
                }

                // Validasi panjang NISN
                if (strlen($nisn) > 20) {
                    $errors[] = "Baris " . ($i+1) . ": NISN terlalu panjang (max 20 karakter)";
                    $errorCount++;
                    continue;
                }

                // Normalisasi status
                $status = in_array(strtolower($status), ['lulus', 'tidak lulus']) ? 
                         ucfirst(strtolower($status)) : 'Lulus';

                // Query untuk insert/update data
                $query = "INSERT INTO siswa (nisn, nama, kelas, absen, status) 
                         VALUES ('" . mysqli_real_escape_string($conn, $nisn) . "', 
                                '" . mysqli_real_escape_string($conn, $nama) . "', 
                                '" . mysqli_real_escape_string($conn, $kelas) . "', 
                                $absen, 
                                '" . mysqli_real_escape_string($conn, $status) . "')
                         ON DUPLICATE KEY UPDATE 
                         nama=VALUES(nama), kelas=VALUES(kelas), 
                         absen=VALUES(absen), status=VALUES(status)";

                if (mysqli_query($conn, $query)) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Baris " . ($i+1) . ": " . mysqli_error($conn);
                }
            }

            if ($successCount > 0) {
                $showSuccessAlert = true;
            }

            $importResult = [
                'success' => $successCount,
                'errors' => $errorCount,
                'details' => $errors
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Import Data Siswa</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .file-upload:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        .notification {
            animation: slideIn 0.5s ease-out, fadeOut 3s 2s ease-out forwards;
        }
        @keyframes slideIn {
            0% { transform: translateY(-100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="fixed top-0 left-0 w-full z-50">
        <?php include 'includes/admin_header.php'; ?>
    </div>

    <?php if ($showSuccessAlert): ?>
    <div class="fixed top-20 right-4 z-50 notification">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg flex items-center space-x-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <div>
                <p class="font-semibold">Import Berhasil!</p>
                <p class="text-sm"><?= $importResult['success'] ?> data berhasil diimport</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="max-w-4xl mx-auto px-4 py-8 mt-32 animate__animated animate__fadeInUp">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-4 md:mb-0">
                    <i class="fas fa-file-import text-blue-500 mr-3"></i>
                    Import Data Siswa
                </h1>
                <a href="siswa.php" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition-all flex items-center">
                    <i class="fas fa-users mr-2"></i> Lihat Data Siswa
                </a>
            </div>

            <div class="bg-blue-50 p-6 rounded-lg mb-8">
                <h3 class="text-xl font-semibold text-blue-800 mb-4">Petunjuk Import:</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>File harus dalam format CSV</li>
                    <li>Gunakan <strong class="text-blue-600">titik koma (;)</strong> sebagai pemisah kolom</li>
                    <li>Format kolom: <strong class="text-blue-600">NISN;Nama;Kelas;Absen;Status</strong></li>
                    <li>Contoh isi: <code class="bg-gray-100 px-2 py-1 rounded">123456;John Doe;XII TKJ 1;1;Lulus</code></li>
                    <li>Status harus "Lulus" atau "Tidak Lulus"</li>
                </ol>
                <div class="mt-4 flex items-center space-x-3">
                    <p class="text-sm text-gray-600">Pastikan file CSV Anda menggunakan tanda titik koma (;) sebagai pemisah!</p>
                </div>
                <div class="mt-6 bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h4 class="font-medium text-gray-800">Template CSV Siap Pakai</h4>
                            <p class="text-sm text-gray-600">Unduh template yang sudah sesuai format</p>
                        </div>
                        <a href="../templates/template.csv" download="template_siswa.csv" 
                           class="bg-green-100 text-green-700 px-6 py-2 rounded-lg hover:bg-green-200 transition-all flex items-center justify-center space-x-2
                           hover:shadow-[0_2px_8px_rgba(16,185,129,0.2)]">
                            <i class="fas fa-file-download"></i>
                            <span>Download Template</span>
                        </a>
                    </div>
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Template ini sudah berisi contoh data yang bisa langsung diisi
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="file-upload p-6 bg-white border-2 border-dashed border-blue-200 rounded-lg transition-all relative">
                    <div class="flex flex-col items-center space-y-4" id="upload-container">
                        <label for="csv_file" class="cursor-pointer">
                            <div class="bg-blue-50 p-4 rounded-full">
                                <i class="fas fa-file-csv text-blue-600 text-3xl"></i>
                            </div>
                            <span class="mt-2 block text-sm font-medium text-blue-600">Pilih File CSV</span>
                        </label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                               class="sr-only" onchange="handleFileSelect(event)">
                        <p class="text-sm text-gray-500" id="file-instruction">Format: CSV. Maksimal 5MB</p>
                    </div>
                    <div id="file-preview" class="hidden mt-4 p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file text-green-600"></i>
                            <span class="text-sm font-medium text-green-800" id="file-name"></span>
                            <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                            class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-all flex items-center justify-center">
                        <i class="fas fa-upload mr-2"></i>
                        Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        function handleFileSelect(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('file-preview');
            const fileName = document.getElementById('file-name');
            const uploadContainer = document.getElementById('upload-container');
            const fileInstruction = document.getElementById('file-instruction');

            if (file) {
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
                uploadContainer.classList.add('hidden');
                fileInstruction.classList.add('hidden');
            }
        }

        function clearFile() {
            const fileInput = document.getElementById('csv_file');
            const preview = document.getElementById('file-preview');
            const uploadContainer = document.getElementById('upload-container');
            const fileInstruction = document.getElementById('file-instruction');

            fileInput.value = '';
            preview.classList.add('hidden');
            uploadContainer.classList.remove('hidden');
            fileInstruction.classList.remove('hidden');
        }
    </script>
</body>
</html>