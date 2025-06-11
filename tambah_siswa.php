<?php
include("koneksi_siswa.php");
$db = new SiswaDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kodejurusan = $_POST['kodejurusan'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $nohp = $_POST['nohp'];

    $query = "INSERT INTO siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->koneksi->prepare($query);
    $stmt->bind_param("isssssss", $nisn, $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp);

    if ($stmt->execute()) {
        echo "<script>alert('Data siswa berhasil ditambahkan!'); window.location='datasiswa.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
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
        <h2>Tambah Data Siswa</h2>
        <form method="POST" action="">
            <label>NISN:</label>
            <input type="number" name="nisn" required maxlength="10">

            <label>Nama:</label>
            <input type="text" name="nama" required>

            <label>Jenis Kelamin:</label>
            <select name="jeniskelamin" required>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>

            <label>Jurusan:</label>
            <select name="kodejurusan" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM jurusan");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['kodejurusan']."'>".$row['namajurusan']."</option>";
                }
                ?>
            </select>

            <label>Kelas:</label>
            <select name="kelas" required>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>

            <label>Alamat:</label>
            <input type="text" name="alamat" required>

            <label>Agama:</label>
            <select name="agama" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM agama");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['idAgama']."'>".$row['namaAgama']."</option>";
                }
                ?>
            </select>

            <label>No HP:</label>
            <input type="text" name="nohp" required maxlength="14">

            <button type="submit">Simpan</button>
            <a href="datasiswa.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>
