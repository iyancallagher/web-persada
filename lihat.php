<?php
require 'connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM pengajuan WHERE id = $id");

if (!$query || mysqli_num_rows($query) == 0) {
    echo "Data dengan ID tersebut tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome@6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #63BD47 ;
            height: 25px;
            color: white;
            font-size: 1.5rem;
            border-radius: 15px 15px 0 0;
        }
        .logo{
            display: flex;
            justify-content: center;
            width: 30%;
            margin-bottom: 50px;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            font-size: 1.1rem;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }

        .img-thumbnail {
            max-width: 250px;
            max-height: 250px;
            margin: 10px auto;
        }

        .btn-group a {
            margin-right: 10px;
        }

        .qr-container {
            text-align: center;
            margin-top: 30px;
        }
        @media screen and (max-width: 768px) {
            .card-header {
            background-color: #63BD47 ;
            height: 10px;
            color: white;
            font-size: 1.5rem;
            border-radius: 10px 10px 0 0;
        }
}

    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header text-white text-center rounded-top-4">
        </div>
        <div class="card-body px-5 py-4">
                <div class="text-center mb-4">
            <img src="img/LOGO BIG .png" alt="Logo" class="img-fluid" style="max-width: 200px;">
        </div>

            <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="bg-light text-dark" style="width: 30%;">Kepada Perusahaan</th>
                        <td><?= htmlspecialchars($data['perusahaan']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Perihal</th>
                        <td><?= htmlspecialchars($data['perihal']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">No Surat</th>
                        <td><?= htmlspecialchars($data['no_surat']) ?></td>
                    </tr>
                    <tr>
                        <th class="bg-light text-dark">Tanggal</th>
                        <td><?= date("d F Y", strtotime($data['tanggal'])) ?></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <!-- Informasi Pengesahan -->
            <div class="mt-5">
                <h5 class="text-center mb-4 text-secondary">Disahkan Oleh</h5>
                <div class="text-center">
                    <p class="fw-bold mb-0" style="font-size: 1.2rem;"><?= htmlspecialchars($data['nama']) ?></p>
                    <p class="text-muted" style="margin-top: -4px;"><?= htmlspecialchars($data['jabatan']) ?></p>
                    <p class="fst-italic mt-3" style="font-size: 0.95rem; color: #444;">
                        Surat ini telah disahkan pada tanggal <?= date("d F Y", strtotime($data['tanggal'])) ?> oleh yang bersangkutan 
                        sebagai bukti autentik dan legalitas atas dokumen ini.
                    </p>

                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
