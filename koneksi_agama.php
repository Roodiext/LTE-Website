<?php
class AgamaDatabase {
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

    public function tampil_data_agama() {
        $query = "SELECT idAgama, namaAgama FROM agama";
        $result = $this->koneksi->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function hapus_agama($idAgama) {
        $query = "DELETE FROM agama WHERE idAgama = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("i", $idAgama);

        if ($stmt->execute()) {
            $this->koneksi->query("SET @new_id = 0;");
            $this->koneksi->query("UPDATE agama SET idAgama = (@new_id := @new_id + 1) ORDER BY idAgama;");
            $this->koneksi->query("ALTER TABLE agama AUTO_INCREMENT = 1;");
            return true;
        } else {
            return false;
        }
    }
}
?>
