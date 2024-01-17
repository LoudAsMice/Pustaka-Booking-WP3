<!-- Begin Page Content --> 
<div class="container-fluid"> 
    <div class="row"> 
        <div class="col-lg-9"> 
            <form action="<?= base_url('buku/ubahbuku').'/'.$buku['id'];?>" enctype="multipart/form-data" method="post">
                <div class="form-group row">
                    <label for="judul_buku" class="col-sm-2 col-form-label">Judul Buku</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="judul_buku" name="judul_buku" value="<?= $buku['judul_buku'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('judul_buku');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <select id="id" name="id" class="form-control form-control-user">
                            <option value="<?= $id;?>"><?= $k;?></option>
                            <?php foreach($kategori as $k){?>
                            <option value="<?= $k['id'];?>"><?= $k['kategori'];?></option>
                            <?php }?>
                        </select>
                        <small class="text-danger pl-1"><?= $validation->getError('id');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pengarang" class="col-sm-2 col-form-label">Pengarang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?= $buku['pengarang'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('pengarang');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="penerbit" class="col-sm-2 col-form-label">Penerbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= $buku['penerbit'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('penerbit');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tahun_terbit" class="col-sm-2 col-form-label">Tahun Terbit</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" value="<?= $buku['tahun_terbit'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('tahun_terbit');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="isbn" class="col-sm-2 col-form-label">ISBN</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $buku['isbn'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('isbn');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="stok" name="stok" value="<?= $buku['stok'];?>">
                        <small class="text-danger pl-1"><?= $validation->getError('stok');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dipinjam" class="col-sm-2 col-form-label">Dipinjam</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="dipinjam" name="dipinjam" value="<?= $buku['dipinjam'];?>" readonly>
                        <small class="text-danger pl-1"><?= $validation->getError('dipinjam');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dibooking" class="col-sm-2 col-form-label">Dibooking</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="dibooking" name="dibooking" value="<?= $buku['dibooking'];?>" readonly>
                        <small class="text-danger pl-1"><?= $validation->getError('dibooking');?></small>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-2">
                        Gambar
                    </div>
                    <div class="col-sm-10">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="<?= base_url('assets/img/uploads').'/'.$buku['image'];?>" class="img-thumbnail" alt="">
                            </div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label for="image" class="custom-file-label"> Upload Image</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row justify-content-end">
                    <button type="submit" class="btn btn-primary">Ubah</button>
                    <button class="btn btn-dark" onclick="history.back();return false"> Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>
