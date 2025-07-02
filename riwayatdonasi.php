<?php
// filepath: c:\xampp\htdocs\campus_care\riwayatdonasi.php
include 'koneksi.php';

// Ambil semua data donasi beserta nama campaign dan nama donatur
$query = "SELECT d.id, d.nama_donatur, d.email_donatur, d.jumlah_donasi,d.jenis_donasi, d.waktu_donasi, d.pesan AS nama_campaign
          FROM tb_donasi d
          LEFT JOIN tb_campaigns c ON d.campaign_id = c.id
          ORDER BY d.waktu_donasi DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Donasi - CampusCare</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(24,49,83,0.08);
            padding: 32px 24px;
        }
        h2 {
            text-align: center;
            color: #183153;
            margin-bottom: 28px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #e0e6ed;
            text-align: left;
        }
        th {
            background: #0077ff;
            color: #fff;
        }
        tr:hover {
            background: #f0f6ff;
        }
        @media (max-width: 600px) {
            .container { padding: 10px 2px; }
            table, th, td { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Riwayat Donasi</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Donatur</th>
                    <th>Email</th>
                    <th>Campaign</th>
                    <th>Jenis Donasi</th>
                    <th>Jumlah Donasi</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $jenis_label = '';
                        if ($row['jenis_donasi'] === 'fakir_miskin') {
                            $jenis_label = 'Fakir Miskin';
                        } elseif ($row['jenis_donasi'] === 'yatim_piatu') {
                            $jenis_label = 'Yatim Piatu';
                        } else {
                            $jenis_label = htmlspecialchars($row['jenis_donasi']);
                        }
                        echo "<tr>";
                        echo "<td>".$no++."</td>";
                         echo "<td>".htmlspecialchars($row['nama_donatur'])."</td>";
                        echo "<td>".htmlspecialchars($row['email_donatur'])."</td>";
                        echo "<td>".htmlspecialchars($row['nama_campaign'])."</td>";
                        echo "<td>".$jenis_label."</td>";
                        echo "<td>Rp ".number_format($row['jumlah_donasi'],0,',','.')."</td>";
                        echo "<td>".date('d-m-Y H:i', strtotime($row['waktu_donasi']))."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada riwayat donasi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    <div style="text-align:center; margin: 24px 0;">
        <a href="index.php" style="
            display: inline-block;
            padding: 10px 28px;
            background: linear-gradient(90deg, #0077ff 60%, #0056b3 100%);
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,119,255,0.08);
        ">Kembali ke Menu Utama</a>
    </div>
    </div>
</body>
</html>