<?php

require "session.php";
require "connect.php";

// Contoh ambil username dari session
$nama = $_SESSION['nama'] ?? 'Pengguna';
$email = $_SESSION['email'] ?? 'Pengguna';
$perusahaan = $_SESSION['perusahaan'] ?? 'Pengguna';
$jabatan = $_SESSION['jabatan'] ?? 'Pengguna';
$nomor_hp = $_SESSION['no_hp'] ?? 'Pengguna';



// Proses penyimpanan akun baru
if (isset($_POST['tambah_akun']) && $jabatan === 'Direktur Utama') {
    $new_name = htmlspecialchars($_POST['nama']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_jabatan = htmlspecialchars($_POST['jabatan']);
    $new_username = htmlspecialchars($_POST['username']);
    $new_perusahaan = htmlspecialchars($_POST['perusahaan']);
    $new_no_hp = htmlspecialchars($_POST['no_hp']);
    $new_password = password_hash($_POST['passwords'], PASSWORD_DEFAULT);

    // Periksa apakah email sudah digunakan
    $query_check = "SELECT * FROM pengguna WHERE email = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $new_email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        // Simpan ke database
        $query_insert = "INSERT INTO pengguna (nama, username, passwords, email, jabatan, perusahaan, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("sssssss", $new_name, $new_username, $new_password, $new_email, $new_jabatan, $new_perusahaan, $new_no_hp);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Akun berhasil ditambahkan!'); window.location.href = 'profil.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan akun!');</script>";
        }
    }

    $stmt_check->close();
    $stmt_insert->close();
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qsignet - Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
body {
    display: flex;
    min-height: 100vh;
    flex-direction: column; /* Tambahkan ini agar konten bisa lebih fleksibel */
}


        .content {
            margin-top: 150px;
            flex-grow: 1;
            padding: 20px;
            font-family: 'poppins';
            transition: margin-left 0.3s ease-in-out;
        }
        body.dark .sidebar {
  background-color: #1e1e1e !important;
}

body.dark .sidebar a {
  color: #ddd !important;
  border-color: rgba(255, 255, 255, 0.1);
}

body.dark .sidebar a:hover {
  color: #fff !important;
}

body.dark .mode-toggle {
  color: #fff !important;
}

        .card {
            max-width: 100%;
            width: 100%;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            text-align: start;
        }
        .card .monthly{
            color: blue;
        }
        .card .annual{
            color: green;
        }
        .card .task{
            color: orange;
        }
        .card .pending{
            color: maroon;
        }
        canvas {
            max-width: 100%;
            max-height: 300px;
        }
        .profile-card {
    background: #fff;
    padding: 1.5rem 2rem;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}
body.dark .profile-card {
  background: #1e1e1e;
  color: #f1f1f1;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
}

body.dark .profile-name {
  color: #f1f1f1;
}

body.dark .info-label,
body.dark .info-value,
body.dark .text-muted {
  color: #ccc !important;
}

body.dark .badge-jabatan {
  background: linear-gradient(45deg, #20c997, #0dcaf0); /* ganti agar tetap kontras di dark mode */
  color: #fff;
}

body.dark .info-icon {
  color: #fff !important;
}
.btn-custom {
    background-color: #27445D;
    color: #fff;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 30px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-custom i {
    font-size: 18px;
}

.btn-custom:hover {
    background-color: #1b2d3c;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

body.dark .btn-custom {
    background-color: #007bff;
}

body.dark .btn-custom:hover {
    background-color: #0056b3;
}
.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid #0d6efd;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.profile-image:hover {
    transform: scale(1.05);
}

.profile-name {
    margin: 0.75rem 0 0.25rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: #333;
}

.badge-jabatan {
    background: linear-gradient(45deg, #198754, #20c997);
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-block;
}

.profile-info {
    flex: 1;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.6rem;
}

.info-icon {
    font-size: 1rem;
    margin-right: 0.6rem;
    width: 20px;
    text-align: center;
}

.info-label {
    font-weight: 500;
    margin-right: 0.5rem;
    min-width: 70px;
    color: #555;
}

.info-value {
    font-weight: 600;
    color: #222;
}

        @media screen and (max-width: 768px) {

                }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <div class="container">
<div class="row justify-content-center ">
    <div class="col-md-10">
        <div class="profile-card">
            <div class="text-center mb-4">
                <div class="profile-image-wrapper">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($nama); ?>&background=0D8ABC&color=fff&size=200" 
                         class="profile-image" 
                         alt="Foto Profil">
                </div>
                <h4 class="profile-name"><?php echo htmlspecialchars($nama); ?></h4>
                <span class="badge-jabatan"><?php echo htmlspecialchars($jabatan); ?></span>
            </div>
            <hr class="mb-4">
            <div class="profile-info px-4">
                <div class="info-item">
                    <i class="fas fa-user info-icon text-primary"></i>
                    <div>
                        <small class="text-muted">Nama Lengkap</small><br>
                        <span class="info-text"><?php echo htmlspecialchars($nama); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone info-icon text-success"></i>
                    <div>
                        <small class="text-muted">Nomor HP</small><br>
                        <span class="info-text"><?php echo htmlspecialchars($nomor_hp); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-building info-icon text-warning"></i>
                    <div>
                        <small class="text-muted">Perusahaan</small><br>
                        <span class="info-text"><?php echo htmlspecialchars($perusahaan); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-briefcase info-icon text-info"></i>
                    <div>
                        <small class="text-muted">Jabatan</small><br>
                        <span class="info-text"><?php echo htmlspecialchars($jabatan); ?></span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope info-icon text-danger"></i>
                    <div>
                        <small class="text-muted">Email</small><br>
                        <span class="info-text"><?php echo htmlspecialchars($email); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<div class="button text-center">
        <?php if ($jabatan === 'Direktur Utama') : ?>
            <a href="register.php" class="btn-custom mt-3 mb-5"><i class="fa fa-user-plus"></i> Tambah Akun</a>
        <?php endif; ?>
    </div>
</body>

</html>
