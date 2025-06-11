<?php 
include("koneksi_siswa.php"); 
$db = new Database();
$data_siswa = $db->tampil_data_siswa();  
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
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
                width: 100%;
                max-width: 1000px;
                margin: 20px auto;
                border-collapse: collapse;
                background: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 15px;
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
            transition: .3s ease-in-out;
        }
        .btn-add:hover {
            background: #0056b3;
        }

        /* Styling DataTables wrapper */
    .dataTables_wrapper {
    max-width: 1000px;
    margin: 0 auto;
    text-align: left;
    font-size: 14px;
    }

    /* Entry selector dan search input */
    .dataTables_length label,
    .dataTables_filter label {
    font-weight: bold;
    color: #333;
    }

    .dataTables_length select,
    .dataTables_filter input {
    padding: 6px 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-left: 8px;
    }

    /* Styling pagination */
    .dataTables_paginate {
    margin-top: 15px;
    text-align: center;
    }

    .dataTables_paginate .paginate_button {
        background-color: #007BFF;
        color: white !important;
        border: none;
        padding: 6px 12px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .dataTables_paginate .paginate_button:hover {
     background-color: #0056b3;
    }

    .dataTables_paginate .paginate_button.current {
        background-color: #0056b3 !important;
        font-weight: bold;
    }

    /* Styling info text */
    .dataTables_info {
        margin-top: 10px;
        color: #666;
    }

    </style>
   
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

</head>
<body>

    <h2>Data Siswa</h2>

    <table id="tabelSiswa">
    <thead>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Jurusan</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>No HP</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($data_siswa as $row) {
            $jeniskelamin = $row['jeniskelamin'] == 'L' ? 'Laki-laki' : 'Perempuan';
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo htmlspecialchars($row['nisn']); ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td><?php echo htmlspecialchars($jeniskelamin); ?></td>
            <td><?php echo htmlspecialchars($row['namajurusan']); ?></td>
            <td><?php echo htmlspecialchars($row['kelas']); ?></td>
            <td><?php echo htmlspecialchars($row['alamat']); ?></td>
            <td><?php echo htmlspecialchars($row['namaAgama']); ?></td>
            <td><?php echo htmlspecialchars($row['nohp']); ?></td>
            <td>
                <a href="edit_siswa.php?nisn=<?php echo urlencode($row['nisn']); ?>" class="btn btn-edit">Edit</a>
                <a href="hapus_siswa.php?nisn=<?php echo urlencode($row['nisn']); ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>


    <a href="tambah_siswa.php" class="btn-add">Tambah Data</a>

    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tabelSiswa').DataTable({
            "lengthMenu": [5, 10, 25, 50, 100]
        });
    });
</script>


</body>
</html>
