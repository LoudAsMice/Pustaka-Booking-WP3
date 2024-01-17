<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?= session()->getFlashdata('pesan');?>
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#bukuBaruModal"><i class="fas fa-file-alt"> Buku Baru</i></a>
            <table class="table table-responsive table-hover" id="dataTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Pengarang</th>
                        <th scope="col">Penerbit</th>
                        <th scope="col">Tahun Terbit</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Dipinjam</th>
                        <th scope="col">Dibooking</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Pilihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $a = 1;
                        foreach($buku as $b) {?>
                    <tr>
                        <th scope="row"><?= $a++; ?></th>
                        <th><?= $b['judul_buku'];?></th>
                        <th><?= $b['pengarang'];?></th>
                        <th><?= $b['penerbit'];?></th>
                        <th><?= $b['tahun_terbit'];?></th>
                        <th><?= $b['isbn'];?></th>
                        <th><?= $b['stok'];?></th>
                        <th><?= $b['dipinjam'];?></th>
                        <th><?= $b['dibooking'];?></th>
                        <td>
                            <picture>
                                <source srcset="" type="image/svg+xml">
                                <img src="<?= base_url('assets/img/upload').'/'.$b['image'];?>" class="img-fluid img-thumbnail" alt="...">
                            </picture>
                        </td>
                        <td>
                            <a href="<?= base_url('buku/ubahbuku').'/'.$b['id'];?>" class="badge badge-info"><i class="fas fa-edit"></i> Ubah</a>
                            <a href="<?= base_url('buku/hapusbuku').'/'.$b['id'];?>" class="badge badge-danger" onclick="return confirm('Anda yakin akan menghapus <?= $judul.' '.$b['judul_buku']?>?');"><i class="fas fa-trash"></i> Hapus</a>
                        </td>
                        <?php }?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End of Main Content -->

<!-- Modal Tambah buku baru -->
<div class="modal fade" id="bukuBaruModal" tabindex="-1" role="dialog" aria-labelledby="bukuBaruModalLabel" aria-hidden="true"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bukuBaruModalLabel">
                    Tambah Buku
                </h5>
                <button type="submit" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('buku');?>" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" name="judul_buku" id="judul_buku" class="form-control form-control-user" placeholder="Masukkan Judul Buku">
                    <small class="text-danger pl-1"><?= $validation->getError('judul_buku');?></small>
                </div>
                <div class="form-group">
                    <select name="id_kategori" class="form-control form-control-user">
                        <option>Pilih Kategori</option>
                        <?php foreach($kategori as $k){?>
                        <option value="<?= $k['id'];?>"><?= $k['kategori'];?></option>
                        <?php }?>
                    </select>
                    <small class="text-danger pl-1"><?= $validation->getError('id_kategori');?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="pengarang" name="pengarang" placeholder="Masukkan Nama Pengarang">
                    <small class="text-danger pl-1"><?= $validation->getError('pengarang');?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="penerbit" name="penerbit" placeholder="Masukkan Nama Penerbit">
                    <small class="text-danger pl-1"><?= $validation->getError('penerbit');?></small>
                </div>
                <div class="form-group">
                    <select name="tahun_terbit" class="form-control form-control-user">
                        <option value="">Pilih Tahun</option>
                        <?php 
                        for ($i=date('Y');$i>1000;$i--) {?>
                        <option value="<?= $i;?>"><?= $i;?></option>
                        <?php } ?>
                    </select>
                    <small class="text-danger pl-1"><?= $validation->getError('tahun_terbit');?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="isbn" name="isbn" placeholder="Masukkan ISBN">
                    <small class="text-danger pl-1"><?= $validation->getError('isbn');?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="stok" name="stok" placeholder="Masukkan Jumlah Stok">
                    <small class="text-danger pl-1"><?= $validation->getError('stok');?></small>
                </div>
                <div class="form-group">
                    <input type="file" name="image" id="image" class="form-control form-control-user">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-ban"></i> Close</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Modal Tambah Menu -->