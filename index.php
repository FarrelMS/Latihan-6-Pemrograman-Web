<?php
require 'koneksi.php';

$result = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
$no = 1;
$pesan = $_GET['pesan'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Pendaftaran Siswa Baru</h1>

    <?php if ($pesan == "tambah") { ?>
        <div class="alert alert-success">Data berhasil ditambahkan.</div>
    <?php } elseif ($pesan == "edit") { ?>
        <div class="alert alert-success">Data berhasil diperbarui.</div>
    <?php } elseif ($pesan == "hapus") { ?>
        <div class="alert alert-success">Data berhasil dihapus.</div>
    <?php } ?>

    <a href="tambah.php" class="btn btn-primary">+ Tambah Siswa</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Agama</th>
                <th>Sekolah Asal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) == 0) { ?>
            <tr>
                <td colspan="7" class="kosong">Belum ada data siswa.</td>
            </tr>
        <?php } ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                <td><?= htmlspecialchars($row['agama']) ?></td>
                <td><?= htmlspecialchars($row['sekolah_asal']) ?></td>
                <td class="aksi">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                    <form action="hapus.php" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>