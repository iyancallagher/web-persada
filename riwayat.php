<?php
require "session.php";
require "connect.php";

// Proses simpan jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $perusahaan = mysqli_real_escape_string($conn, $_POST['perusahaan']);
    $perihal    = mysqli_real_escape_string($conn, $_POST['perihal']);
    $no_surat   = mysqli_real_escape_string($conn, $_POST['nosurat']);
    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);

    $query = "INSERT INTO pengajuan (perusahaan, perihal, no_surat, tanggal)
              VALUES ('$perusahaan', '$perihal', '$no_surat', '$tanggal')";

    if (mysqli_query($conn, $query)) {
        $success = true;
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qsignet - Riwayat QR</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <!-- Bootstrap Table CSS -->
        <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css">

        <!-- jQuery (wajib sebelum bootstrap-table) -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Bootstrap Table JS -->
        <script src="https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js"></script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
            color: #212529;
        }

        .content {
            margin-top: 100px;
            flex-grow: 1;
            padding: 30px 40px;
            font-family: 'poppins', sans-serif;
            transition: margin-left 0.3s ease-in-out;
        }

        .table-container {
            background: white;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .table thead {
            background-color: #1b2d3c;
            color: white;
        }

        @media screen and (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .content {
                padding: 20px;
                overflow: hidden;
            }

            .container-fluid h2 {
                margin-top: 30px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            .table th, .table td {
                white-space: nowrap;
                font-size: 14px;
            }
        }

        /* DARK MODE */
        .dark {
            background-color: #121212;
            color: #f1f1f1;
        }

        .dark .table-container {
            background-color: #1e1e1e;
        }

        .dark .table thead {
            background-color: #2c2c2c;
            color: #f1f1f1;
        }

        .dark .table td,
        .dark .table th {
            background-color: #1e1e1e;
            color: #e0e0e0;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="content">
    <h4>Riwayat QR Tanda Tangan</h4>
    <div class="table-responsive table-container">
        <table id="riwayatTable" class="table table-bordered table-striped" data-toggle="table" 
        data-search="true" 
        data-pagination="true" 
        data-show-columns="true" 
        data-show-refresh="true" 
        data-sortable="true" 
        data-height="600">
            <thead>
                <tr class="text-center">
                    <th data-sortable="true">No</th>
                    <th data-sortable="true">Perusahaan</th>
                    <th data-sortable="true">Perihal</th>
                    <th data-sortable="true">No Surat</th>
                    <th data-sortable="true">Tanggal</th>
                    <th data-sortable="true">QR Image</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $result = mysqli_query($conn, "SELECT * FROM pengajuan ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td class="text-center"><?= $no ?></td>
                    <td><?= htmlspecialchars($row['perusahaan']) ?></td>
                    <td><?= htmlspecialchars($row['perihal']) ?></td>
                    <td><?= htmlspecialchars($row['no_surat']) ?></td>
                    <?php setlocale(LC_TIME, 'id_ID.utf8'); ?>
                    <td><?= strftime('%d %B %Y', strtotime($row['tanggal'])) ?></td>
                    <td class="text-center">
                        <?php if (!empty($row['qr_image']) && file_exists($row['qr_image'])): ?>
                            <img src="<?= $row['qr_image'] ?>" alt="QR Code" width="100">
                        <?php else: ?>
                            <span class="text-danger">QR tidak tersedia</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                $no++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($success) && $success): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Data berhasil disimpan.',
        showConfirmButton: false,
        timer: 1500
    });
</script>
<?php elseif (isset($error)): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: <?= json_encode($error); ?>
    });
</script>
<?php endif; ?>

<!-- Aktifkan dark mode jika sebelumnya dipilih -->
<script>
  window.onload = function () {
    if (localStorage.getItem('mode') === 'dark') {
      document.body.classList.add('dark');
    }
  };
</script>

</body>
</html>
