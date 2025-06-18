<?php require "session.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Qsignet - Beranda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      transition: background-color 0.3s, color 0.3s;
    }

    .content {
      margin-top: 100px;
      padding: 20px;
    }

    .hero {
      background: linear-gradient(135deg, #27445D, #3a5d76);
      padding: 40px 20px;
      border-radius: 20px;
      color: #fff;
      text-align: center;
    }

    .hero h1 {
      font-size: 32px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 16px;
      max-width: 700px;
      margin: 0 auto;
    }

    .features {
      margin-top: 40px;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .feature-card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      text-align: center;
      width: 100%;
      max-width: 260px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .feature-card i {
      font-size: 36px;
      color: #27445D;
      margin-bottom: 10px;
    }

    .feature-card h5 {
      font-size: 18px;
      margin-bottom: 8px;
      font-weight: 600;
    }

    .feature-card p {
      font-size: 14px;
      color: #555;
    }

    .cta {
      text-align: center;
      margin-top: 40px;
    }

    .cta a {
      padding: 12px 25px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 30px;
      background-color: #27445D;
      color: white;
      text-decoration: none;
      transition: background-color 0.3s;
      display: inline-block;
    }

    .cta a:hover {
      background-color: #1b2d3c;
    }

    /* MODE GELAP */
    body.dark {
      background-color: #121212;
      color: #f1f1f1;
    }

    body.dark .feature-card {
      background-color: #1e1e1e;
      color: #f1f1f1;
    }

    body.dark .feature-card i {
      color: #7ec9ff;
    }

    body.dark .feature-card p {
      color: #ccc;
    }

    body.dark .cta a {
      background-color: #007bff;
    }

    body.dark .cta a:hover {
      background-color: #0056b3;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 24px;
      }

      .hero p {
        font-size: 14px;
      }

      .feature-card {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="content">
  <div class="hero">
    <h1><i class="fa fa-qrcode"></i> Sistem Tanda Tangan QR Otomatis</h1>
    <p>
      Kelola dokumen dan buat tanda tangan QR digital secara mudah, cepat, dan aman.
    </p>
  </div>

  <div class="features">
    <div class="feature-card">
      <i class="fas fa-file-signature"></i>
      <h5>Pengajuan Dokumen</h5>
      <p>Input data dan buat QR secara otomatis.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-qrcode"></i>
      <h5>QR Otentik</h5>
      <p>Berisi link & data yang bisa diverifikasi online dan offline.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-history"></i>
      <h5>Riwayat Digital</h5>
      <p>Lacak seluruh arsip dokumen yang telah ditandatangani.</p>
    </div>
    <div class="feature-card">
      <i class="fas fa-print"></i>
      <h5>Cetak Otomatis</h5>
      <p>Hasilkan file PDF lengkap dengan QR dan detail data.</p>
    </div>
  </div>

  <div class="cta">
    <a href="pengajuan.php"><i class="fa fa-plus-circle"></i> Buat Tanda Tangan Baru</a>
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
