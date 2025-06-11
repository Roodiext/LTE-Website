<?php 
include("koneksi_agama.php"); 
$db = new Database();
$data_agama = $db->tampil_data_agama();  
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Agama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            width: 30%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-edit {
            background: #28a745;
            color: white;
        }
        .btn-edit:hover {
            background: #218838;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .btn-add {
            display: inline-block;
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
            transition: 0.3s;
        }
        .btn-add:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Data Agama</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode Agama</th>
            <th>Nama Agama</th>
            <th>Opsi</th>
        </tr>
        <?php
        $no = 1;
        foreach ($data_agama as $row) {
        ?>
        <tr style="text-align: center;">
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['idAgama']); ?></td>
            <td><?php echo htmlspecialchars($row['namaAgama']); ?></td>
            <td>
                <a href="edit_agama.php?idAgama=<?php echo urlencode($row['idAgama']); ?>" class="btn btn-edit">Edit</a>
                <a href="hapus_agama.php?idAgama=<?php echo urlencode($row['idAgama']); ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
            </td>

        </tr>
        <?php
        }
        ?>
    </table>

    <br>
    <a href="tambah_agama.php" style="display: inline-block; padding: 10px; background: blue; color: white; text-decoration: none; border-radius: 5px;">Tambah Data</a>
</body>
</html>
