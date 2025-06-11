<?php
include("koneksi_siswa.php");
$db = new SiswaDatabase();

if (!isset($_GET['nisn'])) {
    echo "<script>alert('NISN tidak ditemukan!'); window.location='datasiswa.php';</script>";
    exit();
}

$nisn = $_GET['nisn'];
$query = "SELECT * FROM siswa WHERE nisn = ?";
$stmt = $db->koneksi->prepare($query);
$stmt->bind_param("i", $nisn);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();

if (!$siswa) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location='datasiswa.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kodejurusan = $_POST['kodejurusan'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $nohp = $_POST['nohp'];

    $query = "UPDATE siswa SET nama=?, jeniskelamin=?, kodejurusan=?, kelas=?, alamat=?, agama=?, nohp=? WHERE nisn=?";
    $stmt = $db->koneksi->prepare($query);
    $stmt->bind_param("ssissisi", $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp, $nisn);

    if ($stmt->execute()) {
        echo "<script>alert('Data siswa berhasil diperbarui!'); window.location='datasiswa.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background: darkblue;
        }
        .btn-cancel {
            display: block;
            text-align: center;
            padding: 10px;
            background: red;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-cancel:hover {
            background: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Data Siswa</h2>
        <form method="POST" action="">
            <label>NISN:</label>
            <input type="number" name="nisn" value="<?php echo htmlspecialchars($siswa['nisn']); ?>" readonly>

            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>

            <label>Jenis Kelamin:</label>
            <select name="jeniskelamin" required>
                <option value="L" <?php echo ($siswa['jeniskelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="P" <?php echo ($siswa['jeniskelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
            </select>

            <label>Jurusan:</label>
            <select name="kodejurusan" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM jurusan");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['kodejurusan'] == $siswa['kodejurusan']) ? 'selected' : '';
                    echo "<option value='".$row['kodejurusan']."' $selected>".$row['namajurusan']."</option>";
                }
                ?>
            </select>

            <label>Kelas:</label>
            <input type="text" name="kelas" value="<?php echo htmlspecialchars($siswa['kelas']); ?>" required>

            <label>Alamat:</label>
            <input type="text" name="alamat" value="<?php echo htmlspecialchars($siswa['alamat']); ?>" required>

            <label>Agama:</label>
            <select name="agama" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM agama");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['idAgama'] == $siswa['agama']) ? 'selected' : '';
                    echo "<option value='".$row['idAgama']."' $selected>".$row['namaAgama']."</option>";
                }
                ?>
            </select>

            <label>No HP:</label>
            <input type="number" name="nohp" value="<?php echo htmlspecialchars($siswa['nohp']); ?>" required>

            <button type="submit">Simpan</button>
            <a href="datasiswa.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>
