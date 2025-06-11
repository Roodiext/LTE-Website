<?php
class SiswaDatabase {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "sekolah";
    public $koneksi;

    public function __construct() {
        $this->koneksi = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->koneksi->connect_error) {
            die("Koneksi database gagal: " . $this->koneksi->connect_error);
        }
    }

    public function tampil_data_siswa() {
        $query = "SELECT 
                    s.nisn, s.nama, s.jeniskelamin, 
                    j.namajurusan, s.kelas, s.alamat, 
                    a.namaAgama, s.nohp
                  FROM siswa s
                  JOIN jurusan j ON s.kodejurusan = j.kodejurusan
                  JOIN agama a ON s.agama = a.idAgama";
        $result = $this->koneksi->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function hapus_siswa($nisn) {
        $query = "DELETE FROM siswa WHERE nisn = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("s", $nisn);
        return $stmt->execute();
    }
}
?>
