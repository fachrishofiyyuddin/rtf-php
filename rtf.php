<?php
session_start();
include "koneksi/index.php";

// Pastikan ID ada dalam parameter GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Siapkan perintah SQL untuk mengambil data berdasarkan ID
    $perintah_sql = mysqli_prepare($koneksi, "SELECT * FROM mahasiswa WHERE id=?");
    mysqli_stmt_bind_param($perintah_sql, "s", $id);
    mysqli_stmt_execute($perintah_sql);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($perintah_sql);

    // Periksa apakah data ditemukan
    if ($data = mysqli_fetch_assoc($result)) {
        // Ambil data dari hasil query
        $nama = $data['nama'];
        $nim = $data['nim'];
        $prodi = $data['prodi'];

        // Membaca template dokumen RTF
        $document = file_get_contents("file.rtf");

        // Menggantikan placeholder dengan data
        $document = str_replace("#NAMA", $nama, $document);
        $document = str_replace("#NIM", $nim, $document);
        $document = str_replace("#PRODI", $prodi, $document);

        // Mengatur header untuk file RTF
        header("Content-type: application/rtf");
        header("Content-disposition: inline; filename=biodata.doc");
        header("Content-length: " . strlen($document));

        // Mengirimkan konten RTF ke client
        echo $document;
        exit();
    } else {
        // Jika data tidak ditemukan, arahkan ke halaman error
        $pesan = "Data gagal dicetak.";
        $_SESSION['gagal_cetak'] = $pesan;
        header("Location: $url");
        exit();
    }
} else {
    // Jika ID tidak diberikan, arahkan ke halaman error
    $pesan = "ID tidak ditemukan.";
    $_SESSION['gagal_cetak'] = $pesan;
    header("Location: $url");
    exit();
}
