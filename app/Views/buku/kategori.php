<!-- Begin Page Content --> 
<div class="container-fluid">
    <?= session()->getFlashdata('pesan');?>
    <center>
        <div class="col-lg-5">
            <?php if(!$validation->hasError('kategori')){ ?>
            <div class="alert alert-danger" role="alert">
            <?= $validation->getError('kategori');?>
            </div>
            <?php }?>
            <a href="" class="btn btn-primary mb-3" data-toggle="modal" data-target="#kategoriBaruModal"><i class="fas fa-file-alt"></i> Tambah Kategori</a>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Pilihan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $a = 1;
                    foreach ($kategori as $k) { ?>
                    <tr>
                        <th scope="row"><?= $a++;?></th>
                        <td><?= $k['kategori'];?></td>
                        <td>
                            <a href="<?= base_url('buku/ubahkategori').'/'.$k['id'];?>" class="badge badge-info">
                            <i class="fas fa-edit"> Ubah</i></a>
                            <a href="<?= base_url('buku/hapuskategori').'/'.$k['id'];?>" onclick="return confirm('Kamu yakin akan menghapus <?= $judul.' '.$k['kategori'];?>?');" class="badge badge-danger">
                            <i class="fas fa-trash"> Hapus</i></a>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </center>
</div>
<!-- /.container-fluid -->

<!-- Modal Tambah kategori baru--> 
<div class="modal fade" id="kategoriBaruModal" tabindex="-1" role="dialog" aria-labelledby="kategoriBaruModalLabel" aria-hidden="true"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriBaruModalLabel">
                    Tambah Kategori
                </h5>
                <button type="submit" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('buku/kategori');?>" method="post">
            <div class="modal-body">
                <div class="form-group">
                    <select name="kategori" id="kategori" class="form-control form-control-user">
                        <option value="">Pilih Kategori</option>
                        <?php $k = ['Sains','Hobby','Komputer','Komunikasi','Hukum','Agama','Populer','Bahasa','Komik'];
                        for ($i=0;$i<9;$i++) {?>
                        <option value="<?= $k[$i];?>"><?= $k[$i];?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-ban"> Close</i>
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle"> Tambah</i>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>