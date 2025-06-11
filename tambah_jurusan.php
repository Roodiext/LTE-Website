<?php 
include("koneksi_jurusan.php");
$db = new JurusanDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namajurusan = $_POST['namajurusan'];

 
    $query = "INSERT INTO jurusan (namajurusan) VALUES (?)";
    $stmt = $db->koneksi->prepare($query);

    
    $stmt->bind_param("s", $namajurusan);

    if ($stmt->execute()) {
        echo "<script>alert('Data jurusan berhasil ditambahkan!'); window.location='datajurusan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Jurusan</title>
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
        input {
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
        <h2>Tambah Data Jurusan</h2>
        <form method="POST" action="">

            <label>Nama Jurusan:</label>
            <input type="text" name="namajurusan" required>

            <button type="submit">Simpan</button>
            <a href="datajurusan.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>
