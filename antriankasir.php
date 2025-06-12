<?php
include "db_config.php";

// Function to safely get POST variables with fallback
function getPost($key, $default = '') {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

// Custom function to format date (placeholder, implement as needed)
function Valid_SetTgl($date) {
    // Assuming input date is in 'Y-m-d' format, return as is or format accordingly
    return $date;
}

$tglAwal = Valid_SetTgl(getPost('dateStart', date('Y-m-01')));
$tglAkhir = Valid_SetTgl(getPost('dateEnd', date('Y-m-d')));
$searchText = trim(getPost('searchText', ''));

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$pas = ""; // Additional condition if needed

$tgl = "rawat_jl_dr.tgl_perawatan BETWEEN '$tglAwal' AND '$tglAkhir' $pas";

$sql = "SELECT rawat_jl_dr.no_rawat, reg_periksa.no_rkm_medis, pasien.nm_pasien,
        jns_perawatan.nm_perawatan, rawat_jl_dr.kd_dokter, dokter.nm_dokter,
        rawat_jl_dr.tgl_perawatan, rawat_jl_dr.jam_rawat, rawat_jl_dr.biaya_rawat
        FROM pasien 
        INNER JOIN reg_periksa ON reg_periksa.no_rkm_medis = pasien.no_rkm_medis
        INNER JOIN rawat_jl_dr ON rawat_jl_dr.no_rawat = reg_periksa.no_rawat
        INNER JOIN jns_perawatan ON rawat_jl_dr.kd_jenis_prw = jns_perawatan.kd_jenis_prw
        INNER JOIN dokter ON rawat_jl_dr.kd_dokter = dokter.kd_dokter
        WHERE $tgl 
        AND (
            rawat_jl_dr.no_rawat LIKE '%$searchText%' OR 
            reg_periksa.no_rkm_medis LIKE '%$searchText%' OR 
            pasien.nm_pasien LIKE '%$searchText%' OR 
            jns_perawatan.nm_perawatan LIKE '%$searchText%' OR 
            rawat_jl_dr.kd_dokter LIKE '%$searchText%' OR 
            dokter.nm_dokter LIKE '%$searchText%'
        )
        ORDER BY rawat_jl_dr.no_rawat DESC";

$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Nama Pasien</title>
    <link href="../assert/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffc0cb;
            color: #b22222;
            padding: 20px;
        }
        table {
            background-color: #ffe4e1;
            color: #b22222;
            width: 90%;
            border-collapse: collapse;
            border: 1px solid #b22222;
            border-radius: 5px;
            margin: 0 auto;
        }
        th, td {
            border-bottom: 1px solid #b22222;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #ffb6c1;
        }
        td.name-cell {
            cursor: pointer;
            color: #b22222;
        }
        #filterForm {
            text-align: center;
            margin-bottom: 20px;
        }
        #filterForm input[type="date"],
        #filterForm input[type="text"] {
            margin: 0 5px;
            padding: 5px;
            border: 1px solid #b22222;
            border-radius: 4px;
        }
        #filterForm button {
            padding: 5px 10px;
            background-color: #ff69b4;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="filterForm">
        <h1>Halaman Pemanggil Pasien</h1>
        <form method="POST" action="">
            <label for="dateStart">Tanggal Awal:</label>
            <input type="date" id="dateStart" name="dateStart" value="<?php echo htmlspecialchars($tglAwal); ?>">
            <label for="dateEnd">Tanggal Akhir:</label>
            <input type="date" id="dateEnd" name="dateEnd" value="<?php echo htmlspecialchars($tglAkhir); ?>">
            <input type="text" id="searchText" name="searchText" placeholder="Cari Nama Pasien..." value="<?php echo htmlspecialchars($searchText); ?>">
            <button type="submit">Filter</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>No Rawat</th>
                <th>No RM</th>
                <th>Nama Pasien</th>
                <th>Nama Perawatan (Poli)</th>
                <th>Kode Dokter</th>
                <th>Nama Dokter</th>
                <th>Tanggal Perawatan</th>
                <th>Jam Rawat</th>
                <th>Biaya Rawat</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['no_rawat']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_rkm_medis']); ?></td>
                        <td class="name-cell"><?php echo htmlspecialchars($row['nm_pasien']); ?></td>
                        <td><?php echo htmlspecialchars($row['nm_perawatan']); ?></td>
                        <td><?php echo htmlspecialchars($row['kd_dokter']); ?></td>
                        <td><?php echo htmlspecialchars($row['nm_dokter']); ?></td>
                        <td><?php echo htmlspecialchars($row['tgl_perawatan']); ?></td>
                        <td><?php echo htmlspecialchars($row['jam_rawat']); ?></td>
                        <td><?php echo htmlspecialchars($row['biaya_rawat']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9" style="padding: 8px;">Tidak ada data pasien.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="../ResponsiveVoiceJS/responsivevoice.js"></script>
    <script>
        function announceClient(name) {
            var message = "Atas nama " + name + " Silahkan ke meja Kasir";
            responsiveVoice.speak(message, "Indonesian Female");
        }

        // Announce pasien name on clicking the name cell
        document.querySelectorAll('td.name-cell').forEach(function(cell) {
            cell.addEventListener('click', function() {
                announceClient(this.textContent);
            });
        });
    </script>
    <footer class="footer" style="color:#b22222; text-align: center; margin-top: 20px;">
        <p>&copy; Made by RVL <?php echo date("Y"); ?></p>
    </footer>
</body>
</html>
<?php
$mysqli->close();
?>
