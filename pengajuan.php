<?php
session_start();
require "connect.php";
require_once "phpqrcode/qrlib.php";

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Cek sesi pengguna
if (!isset($_SESSION['nama'])) {
    header('Location: login.php');
    exit();
}

// Fungsi sanitasi input
function validateInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token tidak valid.");
    }

    $password_input = $_POST['password_confirm'] ?? '';
    if (!isset($_SESSION['password_hash']) || !password_verify($password_input, $_SESSION['password_hash'])) {
        $error = "Password tidak sesuai.";
    } else {
        $perusahaan = validateInput($_POST['perusahaan']);
        $perihal = validateInput($_POST['perihal']);
        $no_surat = validateInput($_POST['nosurat']);
        $tanggal = validateInput($_POST['tanggal']);
        $nama = validateInput($_POST['nama']);
        $jabatan = validateInput($_POST['jabatan']);

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            die("Tanggal tidak valid.");
        }
        if (!preg_match('/^[A-Za-z0-9\-\/]+$/', $no_surat)) {
            die("No Surat tidak valid.");
        }

        $query = "INSERT INTO pengajuan (perusahaan, perihal, no_surat, tanggal, akses, nama, jabatan)
                  VALUES (?, ?, ?, ?, 'online', ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssss", $perusahaan, $perihal, $no_surat, $tanggal, $nama, $jabatan);
        $success = mysqli_stmt_execute($stmt);

        if ($success) {
            $last_id = mysqli_insert_id($conn);
            $qr_url = "http://192.168.1.16/web-persada/lihat.php?id=$last_id";

            $tempQR = "qrcode/temp_qr_$last_id.png";
            QRcode::png($qr_url, $tempQR, QR_ECLEVEL_H, 10);
            $qr_image = imagecreatefrompng($tempQR);
            $width = imagesx($qr_image);
            $height = imagesy($qr_image);

            $logo_path = 'img/logot.png';
            if (file_exists($logo_path)) {
                $logo = imagecreatefrompng($logo_path);
                $logo_size = $width * 0.2;
                $padding = 10;
                $inner_logo_size = $logo_size - (2 * $padding);
                $resized_logo = imagecreatetruecolor($inner_logo_size, $inner_logo_size);
                imagealphablending($resized_logo, false);
                imagesavealpha($resized_logo, true);
                imagecopyresampled($resized_logo, $logo, 0, 0, 0, 0, $inner_logo_size, $inner_logo_size, imagesx($logo), imagesy($logo));
                $logo_with_padding = imagecreatetruecolor($logo_size, $logo_size);
                imagealphablending($logo_with_padding, false);
                imagesavealpha($logo_with_padding, true);
                $transparent = imagecolorallocatealpha($logo_with_padding, 0, 0, 0, 127);
                imagefill($logo_with_padding, 0, 0, $transparent);
                imagecopy($logo_with_padding, $resized_logo, $padding, $padding, 0, 0, $inner_logo_size, $inner_logo_size);
                $logo_x = ($width - $logo_size) / 2;
                $logo_y = ($height - $logo_size) / 2;
                imagecopy($qr_image, $logo_with_padding, $logo_x, $logo_y, 0, 0, $logo_size, $logo_size);
                imagedestroy($logo);
                imagedestroy($resized_logo);
                imagedestroy($logo_with_padding);
            }

            $qr_file = "qrcode/qr_$last_id.png";
            imagepng($qr_image, $qr_file);
            $query = "UPDATE pengajuan SET qr_image = '$qr_file' WHERE id = $last_id";
            mysqli_query($conn, $query);

            imagedestroy($qr_image);
            unlink($tempQR);
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Qsignet - Buat Qr</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f4f6f9; font-family: 'Arial', sans-serif; margin: 0; }
        .container { margin-top: 50px; padding: 20px; }
        .form-control { border-radius: 8px; padding: 10px 15px; margin-bottom: 15px; }
        /* .btn { padding: 10px 20px; border-radius: 8px; } */
        .content { margin-top: 50px; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="mt-5 mb-4">Pengajuan QR</h2>

        <?php if ($success): ?>
            <div class="mt-4">
                <h5 class="text-center text-primary mb-4"><i class="fas fa-qrcode me-2"></i><strong>QR untuk data ini:</strong></h5>
                <div class="text-center mb-3">
                    <img src="<?= $qr_file ?>" alt="QR Code" class="img-thumbnail" style="width: 200px; height: 200px;">
                </div>
                <p class="text-center mb-1">
                    <a href="lihat.php?id=<?= $last_id ?>" target="_blank" class="btn btn-primary form-control btn-sm px-4 py-2">
                        <i class="fas fa-eye me-2"></i> Lihat Data
                    </a>
                </p>
                <div class="text-center">
                    <a href="<?= $qr_file ?>" download class="btn btn-success btn-sm form-control mb-5 px-4 py-2">
                        <i class="fas fa-download me-2"></i> Unduh QR Code
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <form id="pengajuanForm" action="" method="POST">
            <div class="mb-3">
                <label for="perusahaan" class="form-label">Perusahaan Yang Dituju</label>
                <input type="text" name="perusahaan" class="form-control" required placeholder="Perusahaan">
            </div>
            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal</label>
                <input type="text" name="perihal" class="form-control" required placeholder="Perihal">
            </div>
            <div class="mb-3">
                <label for="nosurat" class="form-label">No Surat</label>
                <input type="text" name="nosurat" class="form-control" placeholder="Angka/Urut/Tahun" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>
            <input type="hidden" name="nama" value="<?= htmlspecialchars($_SESSION['nama']) ?>">
            <input type="hidden" name="jabatan" value="<?= htmlspecialchars($_SESSION['jabatan']) ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="password_confirm" id="password_confirm">

            <button type="button" class="btn btn-primary form-control" data-bs-toggle="modal" data-bs-target="#confirmModal">Submit</button>
        </form>
    </div>
</div>

<!-- Modal konfirmasi password -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Konfirmasi Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <label for="modalPassword">Masukkan password Anda:</label>
        <div class="input-group">
            <input type="password" class="form-control" id="modalPassword" placeholder="Password" required>
            <button class="btn mb-2" type="button" id="togglePassword">
                <i class="fas fa-eye fa-lg"></i>    
            </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="submitFormWithPassword()">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

<?php if ($success): ?>
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil disimpan.', showConfirmButton: false, timer: 1500 });
</script>
<?php elseif ($error): ?>
<script>
    Swal.fire({ icon: 'error', title: 'Gagal!', text: <?= json_encode($error); ?> });
</script>
<?php endif; ?>

<script>
function submitFormWithPassword() {
    const pass = document.getElementById("modalPassword").value;
    if (pass === '') return;
    document.getElementById("password_confirm").value = pass;
    document.getElementById("pengajuanForm").submit();
}

// Toggle eye icon
document.getElementById("togglePassword").addEventListener("click", function () {
    const input = document.getElementById("modalPassword");
    const icon = this.querySelector("i");
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
});
</script>

</body>
</html>
