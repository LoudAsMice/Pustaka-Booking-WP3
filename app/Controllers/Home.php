<?php


namespace App\Controllers;

use App\Models\ModelBooking;
use App\Models\ModelBuku;
use App\Models\ModelUser;

class Home extends BaseController
{
    public function index()
    {
        $modelbuku = new ModelBuku(); // model terkoneksi ke db
        $modeluser = new ModelUser(); // model terkoneksi ke db
        $db1 = db_connect(); // connect db
        $db = \Config\Database::connect(); // connect db
        $data = [
            'title' => "Katalog Buku",
            'buku' => $modelbuku->getBuku()->getResultArray(),
            'validation' => \Config\Services::validation(),
            'modelbooking' => new ModelBooking()
        ];

        //jika sudah login dan jika belum login
        if(session('email')){
            $user = $modeluser->cekData(['email' => session('email')])->getRowArray();
            // print_r(session('role_id'));
            $data['user'] = $user['nama'];

            echo view('templates/templates-user/header', $data);
            echo view('buku/daftarbuku', $data);
            echo view('templates/templates-user/modal');
            echo view('templates/templates-user/footer', $data);
        } else {
            $data['user'] = 'Pengunjung';
            echo view('templates/templates-user/header', $data);
            echo view('buku/daftarbuku', $data);
            echo view('templates/templates-user/modal');
            echo view('templates/templates-user/footer');
        }
        
        // print_r($data['buku']);
        // // $data['user'] = $user['nama'];
    }

    public function detailBuku()
    {
        $modelbuku = new ModelBuku();
        $uri1 = new \CodeIgniter\HTTP\URI(); // load uri
        $uri = service('uri'); // load uri
        
        $id = $uri->getSegment(3);
        $buku = $modelbuku->joinKategoriBuku(['buku.id' => $id])->getResultArray();
        // print_r($buku);
        $data = [
            'title' => 'Detail Buku',
            'validation' => \Config\Services::validation()
        ];
        if (session('email')){
            $data['user'] = session('nama');
        } else {
            $data['user'] = 'Pengunjung';
        }

        foreach ($buku as $fields) {
            $data['judul'] = $fields['judul_buku'];
            $data['pengarang'] = $fields['pengarang'];
            $data['penerbit'] = $fields['penerbit'];
            $data['kategori'] = $fields['kategori'];
            $data['tahun'] = $fields['tahun_terbit'];
            $data['isbn'] = $fields['isbn'];
            $data['gambar'] = $fields['image'];
            $data['dipinjam'] = $fields['dipinjam'];
            $data['dibooking'] = $fields['dibooking'];
            $data['stok'] = $fields['stok'];
            $data['id'] = $fields['id'];
        }

        echo view('templates/templates-user/header', $data);
        echo view('buku/detailbuku', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');
    }
}
