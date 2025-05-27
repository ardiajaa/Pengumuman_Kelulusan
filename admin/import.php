<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
redirectIfNotLoggedIn();

$uploadDir = __DIR__ . '/../assets/uploads/siswa/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $csvFile = $_FILES['csv_file']['tmp_name'];

    $csvData = [];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count(array_filter($data)) > 0) {
                $csvData[] = $data;
            }
        }
        fclose($handle);
    }

    $uploadedPhotos = [];
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['photos']['error'][$index] === UPLOAD_ERR_OK) {
                $originalName = $_FILES['photos']['name'][$index];
                $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $newFilename = uniqid() . '.' . $fileExt;
                $targetPath = $uploadDir . $newFilename;

                $check = getimagesize($tmpName);
                if ($check !== false && move_uploaded_file($tmpName, $targetPath)) {
                    $uploadedPhotos[$originalName] = 'siswa/' . $newFilename;
                }
            }
        }
    }

    $successCount = 0;
    $errorCount = 0;
    $errors = [];

    foreach ($csvData as $row) {
        try {
            if (count($row) < 6) {
                throw new Exception("Format baris tidak valid");
            }

            $nisn = trim($row[0]);
            $nama = trim($row[1]);
            $kelas = trim($row[2]);
            $absen = (int) trim($row[3]);
            $tanggalLahir = !empty(trim($row[4])) ? trim($row[4]) : null;
            $status = in_array(strtolower(trim($row[5])), ['lulus', 'tidak lulus']) ?
                ucfirst(strtolower(trim($row[5]))) : 'Lulus';
            $fotoRef = isset($row[6]) ? trim($row[6]) : '';

            if (empty($nisn)) {
                throw new Exception("NISN tidak boleh kosong");
            }

            $fotoPath = null;
            if (!empty($fotoRef)) {
                if (isset($uploadedPhotos[$fotoRef])) {
                    $fotoPath = $uploadedPhotos[$fotoRef];
                } else {
                    throw new Exception("File foto '$fotoRef' tidak ditemukan");
                }
            }

            $stmt = $conn->prepare("INSERT INTO siswa 
                                  (nisn, nama, kelas, absen, tanggal_lahir, status, foto) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)
                                  ON DUPLICATE KEY UPDATE
                                  nama=VALUES(nama), kelas=VALUES(kelas),
                                  absen=VALUES(absen), tanggal_lahir=VALUES(tanggal_lahir),
                                  status=VALUES(status), foto=VALUES(foto)");
            $stmt->bind_param(
                "sssisss",
                $nisn,
                $nama,
                $kelas,
                $absen,
                $tanggalLahir,
                $status,
                $fotoPath
            );

            if (!$stmt->execute()) {
                throw new Exception(mysqli_error($conn));
            }

            $successCount++;
            $stmt->close();
        } catch (Exception $e) {
            $errorCount++;
            $errors[] = "Baris " . ($successCount + $errorCount + 1) . ": " . $e->getMessage();
        }
    }

    $_SESSION['import_result'] = [
        'success' => $successCount,
        'error' => $errorCount,
        'errors' => $errors
    ];

    header("Location: siswa.php?import=done");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include '../includes/header.php'; ?>
    <title>Import Data Siswa dengan Foto</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .preview-images img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="fixed top-0 left-0 w-full z-50">
        <?php include 'includes/admin_header.php'; ?>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8 mt-32 animate__animated animate__fadeInUp">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-file-import text-blue-500 mr-3"></i>
                Import Data Siswa dengan Foto
            </h1>

            <?php
            if (isset($_SESSION['import_result'])):
                $result = $_SESSION['import_result'];
                $successCount = $result['success'];
                $errorCount = $result['error'];
                $errors = $result['errors'];
                unset($_SESSION['import_result']);
                ?>
                <?php if ($successCount > 0): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        Import berhasil: <?= $successCount ?> data ditambahkan/diperbarui.
                    </div>
                <?php endif; ?>

                <?php if ($errorCount > 0): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                        <i class="fas fa-times-circle mr-2"></i>
                        Import gagal: <?= $errorCount ?> data bermasalah.
                        <?php if (!empty($errors)): ?>
                            <ul class="mt-2 list-disc list-inside">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($successCount == 0 && $errorCount == 0 && isset($_GET['import']) && $_GET['import'] == 'done'): ?>
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tidak ada data yang diproses. Pastikan file CSV tidak kosong.
                    </div>
                <?php endif; ?>

            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <i class="fas fa-times-circle mr-2"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>


            <div class="instructions bg-green-50 border-l-4 border-green-600 text-green-800 p-4 mb-6 rounded-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Petunjuk Import:</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Download template CSV terlebih dahulu.</li>
                    <li style="word-break: break-word;">Format kolom:
                        <strong>NISN,Nama,Kelas,Absen,Tanggal_Lahir,Status,Foto</strong></li>
                    <li>Nama file foto di kolom 'Foto' CSV harus sesuai dengan nama file foto yang akan diupload.</li>
                    <li>Format tanggal: <strong>YYYY-MM-DD</strong></li>
                    <li>Status harus "Lulus" atau "Tidak Lulus".</li>
                </ol>
                <p class="text-red-600 font-semibold mt-3">Pastikan nama file foto di CSV sesuai dengan file yang
                    diupload!</p>
                <a href="../templates/template.csv" download
                    class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    <i class="fas fa-download mr-2"></i>Download Template CSV
                </a>
            </div>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="upload-section bg-gray-100 rounded-lg p-6 shadow-md border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5">Unggah File Import</h3>

                    <div class="mb-5">
                        <label for="csv_file" class="block text-sm font-medium text-gray-700 mb-1">File CSV Data
                            Siswa:</label>
                        <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <small class="text-gray-500 text-sm mt-1 block">Pilih file CSV yang berisi data siswa sesuai
                            format template.</small>
                    </div>

                    <div class="mb-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unggah Foto Siswa (Pilih Banyak
                            File):</label>
                        <input type="file" name="photos[]" multiple accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <small class="text-gray-500 text-sm mt-1 block">Pilih semua file foto siswa sekaligus (maks 5MB
                            per file). Nama file foto harus sesuai dengan kolom 'Foto' di CSV.</small>
                        <div class="preview-images mt-3 flex flex-wrap gap-2"></div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-upload mr-2"></i>Import Data
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.querySelector('input[name="photos[]"]').addEventListener('change', function (e) {
            const files = e.target.files;
            const previewDiv = document.querySelector('.preview-images');
            previewDiv.innerHTML = '';

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            const img = document.createElement('img');
                            img.src = event.target.result;
                            img.classList.add('preview-image', 'w-12', 'h-12', 'rounded-md', 'object-cover', 'border', 'border-gray-200'); // Added Tailwind classes
                            img.alt = file.name;
                            previewDiv.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            } else {
            }
        });

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>