<?php
include_once('templates/header.php');
include_once('function.php');

if(isset($_SESSION['role']) && isset($_SESSION['username'])) {
//pengecekan user role bukan admin maka tidak boleh mengakses halaman
if(($_SESSION['role']) != 'admin') {
  echo"<script>alert('anda tidak memiliki akses')</script>";
  echo"<script>window.location.href='index.php'</script>";
  }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800">Data User</h1>
  <!-- Buatkan logikanya apabila tombol simpan di tekan maka jalankan function tambah dan beri notifikasi apabila data sukses atau gagal di masukan -->
  <?php
  // jika ada tombol simpan
  if (isset($_POST['simpan'])) {
    if (tambah_user($_POST) > 0) {
  ?>
      <div class="alert alert-success" role="alert">
        Data berhasil disimpan!
      </div>
    <?php
    } else {
    ?>
      <div class="alert alert-danger" role="alert">
        Data gagal disimpan!
      </div>
  <?php
    }
  }
  else if (isset($_POST['ganti_password'])) {
    if (ganti_password($_POST) > 0) {
    ?>
      <div class="alert alert-success" role="alert">
        Password berhasil diubah!
      </div>  
    <?php
    }else{
    ?>
      <div class="alert alert-danger" role="alert">
        Password gagal diubah!
      </div>  
<?php      
    }  
    }
  ?>

  <!-- Tambahkan sintaks js berikut pada footer.php di atas tag </body> agar alert nya menghilang secara otomatis -->
  <script>
    $(document).ready(function() {
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
          $(this).remove();
        });
      }, 4000);
    });
  </script>
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <button type="button" class="btn btn-primary btn-icon-split"
        data-toggle="modal" data-target="#tambahModal">
        <span class="icon text-white-50">
          <i class="fas fa-plus"></i>
        </span>
        <span class="text">Data User</span>
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>User Role</th>
            <th>Aksi</th>
            </tr>
    </thead>
    <tbody>
    <?php
    // penomoran auto-increment
    $no = 1;
    // Query untuk memanggil semua data dari tabel users
    $users = query("SELECT * FROM users");
    foreach ($users as $user) : ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $user['username'] ?></td>
        <td><?= $user['user_role'] ?></td>
        <td>
            <button type="button" class="btn-info btn-icon-split" data-toggle="modal"
            data-target="#gantiPassword" data-id="<?=$user['id_user']?>">
                <span class="text">Ganti Password</span>
            </button>
            <a class="btn btn-success" href="edit-user.php?id=<?= $user['id_user'] ?>">Ubah</a>
            <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')" href="hapus-user.php?id=<?= $user['id_user'] ?>" class="btn btn-danger">Hapus</a>
        </td>
    </tr>
    <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php
// mengambil data barang dari tabel, dengan kode terbesar
$query = mysqli_query($koneksi, "SELECT max(id_user) as kodeTerbesar FROM users");
$data = mysqli_fetch_array($query);
$kodeuser = $data['kodeTerbesar'];

// mengambil angka dari kode barang terbesar, menggunakan fungsi substr dan diubah ke integer dengan (int)
$urutan = (int) substr($kodeuser, 3, 2);

// nomor yang diambil akan ditambah 1 untuk menentukan nomor urut berikutnya
$urutan++;

// membuat kode barang baru
$huruf = "TMS"; // TMS=Suritenj; berfungsi untuk membuat string menjadi 3 karakter

// angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya tT
$kodeuser = $huruf . sprintf("%02s", $urutan);
?>

<!-- Modal -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form method="post" action="">
    <input type="hidden" name="id_user" id="id_user" value="<?= $kodeuser ?>">
    <div class="form-group row">
        <label for="username" class="col-sm-3 col-form-label">Username</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="username" name="username">
        </div>
    </div>
    <div class="form-group row">
        <label for="password" class="col-sm-3 col-form-label">Password</label>
        <div class="col-sm-8">
            <input type="password" class="form-control" id="password" name="password">
        </div>
    </div>
    <div class="form-group row">
        <label for="user_role" class="col-sm-3 col-form-label">User Role</label>
        <div class="col-sm-8">
            <select class="form-control" id="user_role" name="user_role">
                <option value="admin">Administrator</option>
                <option value="operator">Operator</option>
            </select>
        </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-- Modal Ganti Password -->
<div class="modal fade" id="gantiPassword" tabindex="-1" aria-labelledby="gantiPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gantiPasswordLabel">Ganti Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" name="id_user" id="id_user">
                    <div class="form-group row">
                        <label for="password" class="col-sm-4 col-form-label">Password Baru</label>
                        <div class="col-sm-7">
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="submit" name="ganti_password" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Tambahkan js berikut pada file footer.php sebelum </body> -->
<script>
    // auto hide notifikasi
    $(document).ready(function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 4000);
    });

    // ambil data id user dari tombol ganti password ke modal ganti password
    $('#gantiPassword').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        console.log(id);
        var modal = $(this);
        modal.find('.modal-body #id_user').val(id);
    });
</script>

<!-- /.container-fluid -->
<?php
include_once('templates/footer.php');
?>