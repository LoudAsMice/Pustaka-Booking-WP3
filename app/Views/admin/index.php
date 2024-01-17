 <!-- Begin Page Content -->
 <div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Jumlah Anggota -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $a = $modeluser->getUserWhere(['role_id' => '1'])->countAllResults();
                            echo $a;?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <a href="<?= base_url('user/anggota'); ?>"><i class="fas fa-users fa-3x text-warning"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Buku Terdaftar -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Stok Buku Terdaftar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                            $where = ['stok != 0'];
                            $totalstok = $modelbuku->total('stok', $where);
                            echo $totalstok;?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <a href="<?= base_url('buku'); ?>"><i class="fas fa-book fa-3x text-primary"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Yang Dipinjam -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Buku yang dipinjam</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <?php $where = ['dipinjam != 0'];
                                    $totaldipinjam = $modelbuku->total('dipinjam', $where);
                                    echo $totaldipinjam;?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                            <a href="<?= base_url('user'); ?>"><i class="fas fa-user-tag fa-3x text-success"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Yang Di booking -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Buku Yang Dibooking</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php $where = ['dibooking != 0'];
                            $totaldibooking = $modelbuku->total('dibooking', $where);
                            echo $totaldibooking;?>
                        </div>
                    </div>
                    <div class="col-auto">
                            <a href="<?= base_url('user'); ?>"><i class="fas fa-user-tag fa-3x text-success"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<!-- Divider -->
<hr class="sidebar-divider">

<!-- row table -->
<div class="row">
    <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
        <div class="page-header">
            <span class="fas fa-users text-primary mt-2"> Data user</span>
            <a href="<?= base_url('user/data_user');?>"><i class="fas fa-search text-primary mt-2 float-right"> Tampilkan</i></a>
        </div>
        <div class="table-responsive">
        <table class="table mt-3" id="table-datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Role ID</th>
                    <th>Aktif</th>
                    <th>Member Sejak</th>
                </tr>
            </thead>   
            <tbody>
                <?php 
                $i = 1;
                foreach ($anggota as $a) {
                ?>
                <tr>
                    <td><?= $i++ ;?></td>
                    <td><?= $a['nama'];?></td>
                    <td><?= $a['email'];?></td>
                    <td><?= $a['role_id'];?></td>
                    <td><?= $a['is_active'];?></td>
                    <td><?= date('d/m/Y', $a['tanggal_input']);?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
    </div>    

    <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
        <div class="page-header">
            <span class="fas fa-book text-warning mt-2"> Data Buku</span>
            <a href="<?= base_url('buku');?>"><i class="fas fa-search text-primary mt-2 float-right"> Tampilkan</i></a>
        </div>
        <div class="table-responsive">
            <table class="table mt-3" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun Terbit</th>
                        <th>ISBN</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    foreach ($buku as $b) { ?>
                    <tr>
                        <td><?= $i++;?></td>
                        <td><?= $b['judul_buku'];?></td>
                        <td><?= $b['pengarang'];?></td>
                        <td><?= $b['penerbit'];?></td>
                        <td><?= $b['tahun_terbit'];?></td>
                        <td><?= $b['isbn'];?></td>
                        <td><?= $b['stok'];?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>