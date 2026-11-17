<?php
require 'koneksi.php';

$daftar_agama = ["Islam", "Kristen", "Katolik", "Hindu", "Buddha", "Konghucu"];

$error   = "";
$nama    = "";
$alamat  = "";
$jk      = "";
$agama   = "";
$sekolah = "";

if (isset($_POST['simpan'])) {
    $nama    = trim($_POST['nama'] ?? "");
    $alamat  = trim($_POST['alamat'] ?? "");
    $jk      = $_POST['jenis_kelamin'] ?? "";
    $agama   = $_POST['agama'] ?? "";
    $sekolah = trim($_POST['sekolah_asal'] ?? "");

    if ($nama == "" || $alamat == "" || $jk == "" || $agama == "" || $sekolah == "") {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "INSERT INTO siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $jk, $agama, $sekolah);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Tambah Siswa Baru</h1>

    <?php if ($error != "") { ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" class="form">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>">

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3"><?= htmlspecialchars($alamat) ?></textarea>

        <label>Jenis Kelamin</label>
        <div class="radio-group">
            <label>
                <input type="radio" name="jenis_kelamin" value="Laki-laki"
                    <?= $jk == "Laki-laki" ? "checked" : "" ?>> Laki-laki
            </label>
            <label>
                <input type="radio" name="jenis_kelamin" value="Perempuan"
                    <?= $jk == "Perempuan" ? "checked" : "" ?>> Perempuan
            </label>
        </div>

        <label for="agama">Agama</label>
        <select id="agama" name="agama">
            <option value="">-- Pilih Agama --</option>
            <?php foreach ($daftar_agama as $a) { ?>
                <option value="<?= $a ?>" <?= $agama == $a ? "selected" : "" ?>><?= $a ?></option>
            <?php } ?>
        </select>

        <label for="sekolah_asal">Sekolah Asal</label>
        <input type="text" id="sekolah_asal" name="sekolah_asal" value="<?= htmlspecialchars($sekolah) ?>">

        <div class="form-actions">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>