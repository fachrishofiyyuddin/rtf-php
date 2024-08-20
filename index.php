<?php
ob_start(); // Mulai output buffering

session_start();
include "koneksi/index.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak RTF dengan PHP: Panduan Praktis dan Contoh Langsung</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- sweetalert CDN -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <h2>Cetak RTF dengan PHP: Panduan Praktis dan Contoh Langsung</h2>
        <hr>
        <?php if (!empty($_SESSION['gagal'])) {
        ?>
            <div class="alert alert-danger">
                <strong>Gagal!</strong> <?= $_SESSION['gagal']; ?>
            </div>
        <?php
            unset($_SESSION['gagal']);
            session_destroy();
        } elseif (!empty($_SESSION['berhasil'])) {
        ?>
            <div class="alert alert-success">
                <strong>Berhasil!</strong> <?= $_SESSION['berhasil']; ?>
            </div>
        <?php
            unset($_SESSION['berhasil']);
            session_destroy();
        }; ?>
        <form method="post" class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-sm-2" for="nama">Nama Lengkap:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama" placeholder="Enter Nama Lengkap" name="nama">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="nim">No. Induk Mahasiswa:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nim" placeholder="Enter NIM" name="nim">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="prodi">Program Studi</label>
                <div class="col-sm-10">
                    <select name="prodi" id="prodi" class="form-control">
                        <option disbaled selected>Pilih Program Studi</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Teknik Mesin">Teknik Mesin</option>
                        <option value="Teknik Industri">Teknik Industri</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="BtnSimpan" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
        <hr>
        <?php if (!empty($_SESSION['gagal_cetak'])) {
        ?>
            <div class="alert alert-danger">
                <strong>Gagal!</strong> <?= $_SESSION['gagal_cetak']; ?>
            </div>
        <?php
            unset($_SESSION['gagal_cetak']);
            session_destroy();
        }
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Lengkap</th>
                    <th>No. Induk Mahasiswa</th>
                    <th>Program Studi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $perintah_select = mysqli_query($koneksi, "select * from mahasiswa order by id desc");
                foreach ($perintah_select as $data):
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= stripslashes($data['nama']); ?></td>
                        <td><?= $data['nim']; ?></td>
                        <td><?= $data['prodi']; ?></td>
                        <td>
                            <a href="rtf.php?id=<?= $data['id']; ?>" class="btn btn-success">Cetak</a>
                        </td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
if (isset($_POST['BtnSimpan'])) {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $prodi = $_POST['prodi'];

    if (empty($nama) || empty($nim) || empty($prodi)) {
        $pesan = "Inputan tidak boleh kosong.";
        $_SESSION['gagal'] = $pesan;
        header("Location:$url");
        ob_end_flush(); // Akhiri output buffering dan kirim output
        exit();
    }

    $perintah_stmt_insert = mysqli_prepare($koneksi, "insert into mahasiswa (nama,nim,prodi) values (?,?,?)");
    mysqli_stmt_bind_param($perintah_stmt_insert, 'sss', $nama, $nim, $prodi);
    if (mysqli_stmt_execute($perintah_stmt_insert)) {
        $pesan = "Data berhasil disimpan.";
        $_SESSION['berhasil'] = $pesan;
        header("Location:$url");
        exit();
    } else {
        $pesan = "Data gagal disimpan.";
        $_SESSION['gagal'] = $pesan;
        header("Location:$url");
        ob_end_flush(); // Akhiri output buffering dan kirim output
        exit();
    }
}
?>