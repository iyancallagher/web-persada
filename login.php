<?php
session_start();
require "connect.php";
if (isset($_POST['loginbtn'])) {
    $username = trim($_POST['username']);
    $password = $_POST['passwords']; // Jangan di-htmlspecialchars karena akan dibandingkan secara hash

    // Gunakan prepared statement untuk cegah SQL Injection
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah user ditemukan
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Verifikasi password yang sudah di-hash
        if (password_verify($password, $data['passwords'])) {
            session_regenerate_id(true); // Cegah session fixation

            $_SESSION['nama'] = $data['nama'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['no_hp'] = $data['no_hp'];
            $_SESSION['perusahaan'] = $data['perusahaan'];
            $_SESSION['jabatan'] = $data['jabatan'];
            $_SESSION['id'] = $data['id'];
            $_SESSION['login'] = true;
            $_SESSION['password_hash'] = $data['passwords']; 
            header('Location: index.php');
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Qsignet - Persada Muda Indonesia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #4b6cb7, #182848);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: background-color 0.4s ease;
      padding: 20px;
      box-sizing: border-box;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px 25px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      animation: fadeInUp 1s ease;
    }

    .dark-mode .login-box {
      background-color: rgba(30, 30, 30, 0.95);
      color: white;
    }

    .form-control, .input-group-text {
      border-radius: 8px;
    }

    .btn {
      border-radius: 8px;
    }

    .google-btn {
      background-color: #ffffff;
      border: 1px solid #ddd;
      color: #444;
    }

    .dark-mode {
      background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
    }

    @keyframes fadeInUp {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }

    @media (max-width: 768px) {
      .login-box {
        padding: 20px 15px;
        margin: 0 15px;
      }
    }

    @media (max-width: 480px) {
      .login-box {
        padding: 15px 10px;
        margin: 0 10px;
      }
    }
  </style>
</head>
<body id="body">
  <div class="login-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Login</h4>
      <button class="btn btn-outline-dark btn-sm" id="modeToggle"><i class="fas fa-moon"></i></button>
    </div>
    <form action="" method="post">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Masukan Username" required>
      </div>
      <div class="mb-3">
        <label for="passwords" class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="passwords" id="passwords" class="form-control" placeholder="Masukan Password" required>
          <span class="input-group-text" style="cursor:pointer;"><i class="fa fa-eye" id="togglePasswordIcon"></i></span>
        </div>
      </div>
      <button type="submit" name="loginbtn" class="btn btn-primary w-100">Login</button>
    </form>

    <?php if (isset($error)): ?>
    <div class="alert alert-warning mt-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>

  <script>
    // Toggle Password
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const passwordInput = document.getElementById('passwords');
    togglePasswordIcon.addEventListener('click', function () {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Toggle Dark Mode
    const modeToggle = document.getElementById('modeToggle');
    const body = document.getElementById('body');
    modeToggle.addEventListener('click', function () {
      body.classList.toggle('dark-mode');
      const icon = this.querySelector('i');
      icon.classList.toggle('fa-moon');
      icon.classList.toggle('fa-sun');
    });
  </script>
</body>
</html>
