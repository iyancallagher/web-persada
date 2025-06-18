<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Burger Menu Style ME-QR</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      color: #222;
      transition: background 0.3s, color 0.3s;
    }

    /* HEADER */
    header {
      background: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1001;
    }

    .logo {
      font-weight: bold;
      font-size: 20px;
      color: #333;
    }

    .burger {
      font-size: 22px;
      cursor: pointer;
      background: none;
      border: none;
      color: #333;
    }

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      top: 0;
      margin-top: 40px;
      left: -250px;
      width: 250px;
      height: 100%;
      background: linear-gradient(135deg, #27445D, #4a6583); /* Gradasi sidebar */
      color: white;
      padding: 60px 20px;
      transition: left 0.3s ease-in-out;
      z-index: 1000;
    }

    .sidebar.active {
      left: 0;
    }

    .sidebar a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 0;
      font-size: 16px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .info-sidebar {
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: space-between;
    }

    .menu-links a,
    .logout-link a {
      display: block;
      color: white;
      text-decoration: none;
      padding: 12px 0;
      font-size: 16px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    /* OVERLAY */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.4);
      z-index: 999;
    }

    .overlay.active {
      display: block;
    }

    /* DARK MODE STYLES */
    body.dark {
      background-color: #1a1a1a;
      color: #f1f1f1;
    }

    body.dark header {
      background-color: #222;
      color: #fff;
      box-shadow: none;
    }

    body.dark .burger {
      color: #fff;
    }

    body.dark .sidebar {
      background: linear-gradient(135deg, #111, #222); /* Gradasi sidebar di mode gelap */
    }

    body.dark .sidebar a {
      color: #ccc;
    }

    body.dark .sidebar a:hover {
      color: #fff;
    }
  </style>
</head>
<body>

  <header>
    <button class="burger" id="burgerBtn"><i class="fa fa-bars"></i></button>
    <img src="img/LOGO SMALL PT PERSADA MUDA INDONESIA.png" alt="" width="80">
    <button class="burger" id="toggleModeBtn" title="Ganti Mode"><i class="fas fa-moon" id="modeIcon"></i></button>
  </header>

  <div class="sidebar" id="sidebar">
    <div class="info-sidebar">
      <div class="menu-links">
        <a href="index.php"><i class="fa fa-home"></i> Beranda</a>
        <a href="profil.php"><i class="fa fa-user"></i> Profil</a>
        <a href="pengajuan.php"><i class="fa fa-file-signature"></i> Pengajuan</a>
        <a href="riwayat.php"><i class="fa fa-history"></i> Riwayat</a>
      </div>
      <div class="logout-link">
        <a href="logout.php"><i class="fa fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  </div>

  <div class="overlay" id="overlay"></div>

  <script>
    const burgerBtn = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleModeBtn = document.getElementById('toggleModeBtn');
    const modeIcon = document.getElementById('modeIcon');

    burgerBtn.addEventListener('click', () => {
      sidebar.classList.toggle('active');
      overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    });

    toggleModeBtn.addEventListener('click', () => {
      document.body.classList.toggle('dark');
      const isDark = document.body.classList.contains('dark');
      localStorage.setItem('mode', isDark ? 'dark' : 'light');
      modeIcon.classList = isDark ? 'fas fa-sun' : 'fas fa-moon';
    });

    // Terapkan preferensi mode yang tersimpan
    window.onload = function () {
      if (localStorage.getItem('mode') === 'dark') {
        document.body.classList.add('dark');
        modeIcon.classList = 'fas fa-sun';
      }
    };
  </script>

</body>
</html>