<?php
require 'koneksi.php';

$daftar_agama = ["Islam", "Kristen", "Katolik", "Hindu", "Buddha", "Konghucu"];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data lama berdasarkan id
$stmt = mysqli_prepare($koneksi, "SELECT * FROM siswa WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Jika id tidak ditemukan, kembali ke halaman utama
if (!$data) {
    header("Location: index.php");
    exit;
}

$error = "";

// Simpan perubahan
if (isset($_POST['update'])) {
    $nama    = trim($_POST['nama'] ?? "");
    $alamat  = trim($_POST['alamat'] ?? "");
    $jk      = $_POST['jenis_kelamin'] ?? "";
    $agama   = $_POST['agama'] ?? "";
    $sekolah = trim($_POST['sekolah_asal'] ?? "");

    // Tampilkan kembali isian terbaru di form jika terjadi error
    $data['nama']          = $nama;
    $data['alamat']        = $alamat;
    $data['jenis_kelamin'] = $jk;
    $data['agama']         = $agama;
    $data['sekolah_asal']  = $sekolah;

    if ($nama == "" || $alamat == "" || $jk == "" || $agama == "" || $sekolah == "") {
        $error = "Semua field wajib diisi!";
    } else {
        $stmt = mysqli_prepare($koneksi,
            "UPDATE siswa SET nama=?, alamat=?, jenis_kelamin=?, agama=?, sekolah_asal=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $jk, $agama, $sekolah, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?pesan=edit");
            exit;
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Data Siswa</h1>

    <?php if ($error != "") { ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php } ?>

    <form method="POST" class="form">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']) ?>">

        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>

        <label>Jenis Kelamin</label>
        <div class="radio-group">
            <label>
                <input type="radio" name="jenis_kelamin" value="Laki-laki"
                    <?= $data['jenis_kelamin'] == "Laki-laki" ? "checked" : "" ?>> Laki-laki
            </label>
            <label>
                <input type="radio" name="jenis_kelamin" value="Perempuan"
                    <?= $data['jenis_kelamin'] == "Perempuan" ? "checked" : "" ?>> Perempuan
            </label>
        </div>

        <label for="agama">Agama</label>
        <select id="agama" name="agama">
            <option value="">-- Pilih Agama --</option>
            <?php foreach ($daftar_agama as $a) { ?>
                <option value="<?= $a ?>" <?= $data['agama'] == $a ? "selected" : "" ?>><?= $a ?></option>
            <?php } ?>
        </select>

        <label for="sekolah_asal">Sekolah Asal</label>
        <input type="text" id="sekolah_asal" name="sekolah_asal"
               value="<?= htmlspecialchars($data['sekolah_asal']) ?>">

        <div class="form-actions">
            <button type="submit" name="update" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
</body>
</html>