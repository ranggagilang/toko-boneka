<?php
// Koneksi database
require_once 'koneksi.php';

// Fungsi untuk mendapatkan semua data
function getAllData($conn) {
    $query = "SELECT * FROM boneka ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    $data = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    return $data;
}

// Fungsi untuk mendapatkan data berdasarkan ID
function getDataById($conn, $id) {
    $query = "SELECT * FROM boneka WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk menambah data
function createData($conn, $nama, $jenis, $harga, $stok, $deskripsi) {
    $nama = mysqli_real_escape_string($conn, $nama);
    $jenis = mysqli_real_escape_string($conn, $jenis);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $query = "INSERT INTO boneka (nama, jenis, harga, stok, deskripsi) VALUES ('$nama', '$jenis', $harga, $stok, '$deskripsi')";
    return mysqli_query($conn, $query);
}

// Fungsi untuk update data
function updateData($conn, $id, $nama, $jenis, $harga, $stok, $deskripsi) {
    $nama = mysqli_real_escape_string($conn, $nama);
    $jenis = mysqli_real_escape_string($conn, $jenis);
    $deskripsi = mysqli_real_escape_string($conn, $deskripsi);
    $query = "UPDATE boneka SET nama = '$nama', jenis = '$jenis', harga = $harga, stok = $stok, deskripsi = '$deskripsi' WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Fungsi untuk hapus data
function deleteData($conn, $id) {
    $query = "DELETE FROM boneka WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Proses form
$nama = "";
$jenis = "";
$harga = "";
$stok = "";
$deskripsi = "";
$id = "";
$isEdit = false;

// Proses Create dan Update
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    
    if (isset($_POST['id']) && $_POST['id'] != "") {
        // Update data
        $id = $_POST['id'];
        if (updateData($conn, $id, $nama, $jenis, $harga, $stok, $deskripsi)) {
            header("Location: index.php?pesan=update");
        }
    } else {
        // Create data baru
        if (createData($conn, $nama, $jenis, $harga, $stok, $deskripsi)) {
            header("Location: index.php?pesan=tambah");
        }
    }
}

// Proses Edit (ambil data untuk form)
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $isEdit = true;
    $data = getDataById($conn, $id);
    
    if ($data) {
        $nama = $data['nama'];
        $jenis = $data['jenis'];
        $harga = $data['harga'];
        $stok = $data['stok'];
        $deskripsi = $data['deskripsi'];
    }
}

// Proses Delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (deleteData($conn, $id)) {
        header("Location: index.php?pesan=hapus");
    }
}

// Ambil semua data untuk ditampilkan
$semuaData = getAllData($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Boneka - Sistem Manajemen</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1><i class="fas fa-teddy-bear toy-icon"></i> Toko Boneka - Sistem Manajemen</h1>
        </div>
    </header>

    <div class="container">
        <?php if(isset($_GET['pesan'])): ?>
            <?php if($_GET['pesan'] == "tambah"): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Data boneka berhasil ditambahkan!
                </div>
            <?php elseif($_GET['pesan'] == "update"): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Data boneka berhasil diperbarui!
                </div>
            <?php elseif($_GET['pesan'] == "hapus"): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Data boneka berhasil dihapus!
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Form Tambah/Edit Boneka -->
        <div class="card">
            <div class="card-header">
                <h2><?= $isEdit ? "Edit Data Boneka" : "Tambah Boneka Baru"; ?></h2>
            </div>
            <form action="index.php" method="post">
                <?php if($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $id; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nama">Nama Boneka</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="jenis">Jenis Boneka</label>
                    <select class="form-control" id="jenis" name="jenis" required>
                        <option value="" disabled <?= $jenis == "" ? "selected" : ""; ?>>Pilih Jenis Boneka</option>
                        <option value="Beruang" <?= $jenis == "Beruang" ? "selected" : ""; ?>>Beruang</option>
                        <option value="Barbie" <?= $jenis == "Barbie" ? "selected" : ""; ?>>Barbie</option>
                        <option value="Animal" <?= $jenis == "Animal" ? "selected" : ""; ?>>Animal</option>
                        <option value="Karakter" <?= $jenis == "Karakter" ? "selected" : ""; ?>>Karakter</option>
                        <option value="Lainnya" <?= $jenis == "Lainnya" ? "selected" : ""; ?>>Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="<?= $harga; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $stok; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?= $deskripsi; ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $isEdit ? "Update Boneka" : "Simpan Boneka"; ?>
                    </button>
                    
                    <?php if($isEdit): ?>
                        <a href="index.php" class="btn btn-danger">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Data Boneka -->
        <div class="card">
            <div class="card-header">
                <h2>Daftar Boneka</h2>
            </div>
            
            <?php if(count($semuaData) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Boneka</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($semuaData as $data): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $data['nama']; ?></td>
                                <td><span class="badge"><?= $data['jenis']; ?></span></td>
                                <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
                                <td><?= $data['stok']; ?></td>
                                <td><?= strlen($data['deskripsi']) > 50 ? substr($data['deskripsi'], 0, 50) . '...' : $data['deskripsi']; ?></td>
                                <td class="action-btns">
                                    <a href="index.php?edit=<?= $data['id']; ?>" class="btn btn-success">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="index.php?hapus=<?= $data['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus boneka ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-data">
                    <i class="fas fa-teddy-bear fa-3x"></i>
                    <p>Belum ada data boneka. Silakan tambahkan boneka baru.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
        .badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: var(--secondary);
            color: var(--dark);
        }
    </style>
</body>
</html>