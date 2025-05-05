<?php
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama        = htmlspecialchars($_POST['nama']);
    $email       = htmlspecialchars($_POST['email']);
    $no_hp       = htmlspecialchars($_POST['no_hp']);
    $perusahaan  = htmlspecialchars($_POST['perusahaan']);
    $jabatan     = htmlspecialchars($_POST['jabatan']);
    $username    = htmlspecialchars($_POST['username']);
    $password    = $_POST['password']; // Jangan di-htmlspecialchars untuk bisa di-hash

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah username sudah digunakan
    $cek = $conn->prepare("SELECT id FROM pengguna WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek_result = $cek->get_result();

    if ($cek_result->num_rows > 0) {
        $error = "Username sudah digunakan.";
    } else {
        // Simpan user baru
        $stmt = $conn->prepare("INSERT INTO pengguna (nama, email, no_hp, perusahaan, jabatan, username, passwords) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nama, $email, $no_hp, $perusahaan, $jabatan, $username, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registrasi berhasil. Silakan login.";
        } else {
            $error = "Registrasi gagal: " . $stmt->error;
        }

        $stmt->close();
    }

    $cek->close();
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<h2>Form Registrasi</h2>
<form method="post">
    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Lengkap" required>
    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
    <input type="text" name="no_hp" class="form-control mb-2" placeholder="No HP" required>
    <input type="text" name="perusahaan" class="form-control mb-2" placeholder="Perusahaan" required>
    <input type="text" name="jabatan" class="form-control mb-2" placeholder="Jabatan" required>
    <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
    <button type="submit" class="btn btn-success">Daftar</button>
</form>

<?php if (isset($success)): ?>
    <div class="alert alert-success mt-2"><?= htmlspecialchars($success) ?></div>
<?php elseif (isset($error)): ?>
    <div class="alert alert-danger mt-2"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

</body>
</html>