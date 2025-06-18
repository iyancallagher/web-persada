<?php
require "session.php";
require "connect.php";

// Cek apakah pengguna adalah Direktur Utama
if ($_SESSION['jabatan'] !== 'Direktur Utama') {
    echo "<script>alert('Hanya Direktur Utama yang dapat menambah akun!'); window.location.href = 'profil.php';</script>";
    exit();
}

if (isset($_POST['tambah_akun'])) {
    $new_name = htmlspecialchars($_POST['nama']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_jabatan = htmlspecialchars($_POST['jabatan']);
    $new_username = htmlspecialchars($_POST['username']);
    $new_perusahaan = "Persada Muda Indonesia"; // Perusahaan tetap
    $new_no_hp = htmlspecialchars($_POST['no_hp']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Periksa apakah email sudah digunakan
    $query_check = "SELECT * FROM pengguna WHERE email = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $new_email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href = 'register.php';</script>";
    } else {
        // Simpan ke database
        $query_insert = "INSERT INTO pengguna (nama, username, passwords, email, jabatan, perusahaan, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("sssssss", $new_name, $new_username, $new_password, $new_email, $new_jabatan, $new_perusahaan, $new_no_hp);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Akun berhasil ditambahkan!'); window.location.href = 'profil.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan akun!'); window.location.href = 'register.php';</script>";
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
    <title>Register - QR Tanda Tangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }
        .container{
            display: flex;
            justify-content: center;
        }
        .register-container {
            margin-top: 100px;
            max-width: 450px;
            padding: 30px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            color: #27445D;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #27445D;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #1b2d3c;
        }
        body.dark {
            background-color: #121212;
            color: #f1f1f1;
        }
        body.dark .register-container {
            background-color: #1e1e1e;
            color: #f1f1f1;
        }
        body.dark .btn-primary {
            background-color: #007bff;
        }
        body.dark .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include "sidebar.php"?>
    <div class="container">
        <div class="register-container">
            <h2><i class="fa fa-user-plus"></i> Tambah Akun Baru</h2>
            <form method="POST" action="">
                <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
                <input type="text" class="form-control" name="jabatan" placeholder="Jabatan" required>
                <input type="text" class="form-control" name="no_hp" placeholder="Nomor HP" required>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <button type="submit" name="tambah_akun" class="btn btn-primary">Daftar</button>
            </form>
        </div>
    </div>
    <script>
        // Terapkan dark mode jika tersimpan sebelumnya
        window.onload = function () {
            if (localStorage.getItem('mode') === 'dark') {
                document.body.classList.add('dark');
            }
        };
    </script>
</body>
</html>
