<?php
class JurusanDatabase {
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

    public function tampil_data_jurusan() {
        $query = "SELECT kodejurusan, namajurusan FROM jurusan";
        $result = $this->koneksi->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function hapus_jurusan($kodejurusan) {
        $query = "DELETE FROM jurusan WHERE kodejurusan = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $kodejurusan);
        return $stmt->execute();
    }
}
?>
