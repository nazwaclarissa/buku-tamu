<?php
// panggil file function.php
require_once 'function.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (hapus_user($id) > 0) {
        // jika data berhasil dihapus maka muncul alert
        echo "<script>alert('Data Berhasil dihapus!')</script>";
        echo "<script>window.location.href='buku-tamu.php'</script>";
    } else {
        // jika gagal dihapus
        echo "<script>alert('Data Gagal dihapus!')</script>";
        echo "<script>window.location.href='user.php'</script>";
    }
}
?>
