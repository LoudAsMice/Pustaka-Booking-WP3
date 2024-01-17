<?php

namespace App\Controllers;
date_default_timezone_set('Asia/Jakarta');


use App\Models\ModelBooking;
use App\Models\ModelUser;
use Dompdf\Dompdf;

class Booking extends BaseController
{
    public $db;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        $modelbooking = new ModelBooking();
        $modeluser = new ModelUser();
        $db = \Config\Database::connect();
        $uri = service('uri');
        $id_user = session('id_user');
        $data['booking'] = $modelbooking->joinOrder(['booking.id_user' => $uri->getSegment(2)])->getResult();

        $user = $modeluser->cekData(['email' => session('email')])->getRowArray();

        foreach ($user as $a) {
            $data = [
                'image' => $user['image'],
                'user' => $user['nama'],
                'email' => $user['email'],
                'tanggal_input' => $user['tanggal_input']
            ];
        }

        $dtb = $modelbooking->showTemp(['id_user' => $id_user])->countAllResults();

        if($dtb < 1) {
            session()->setFlashdata('pesan', '<div class="alert alert-message alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            return redirect()->to(base_url());
        } else {
            $data['temp'] = $db->query("SELECT image, judul_buku, penulis, penerbit, tahun_terbit, id_buku FROM temp WHERE id_user = '$id_user'")->getResultArray();
        }

        $data['title'] = "Data Booking";
        $data['validation'] = \Config\Services::validation();

        echo view('templates/templates-user/header', $data);
        echo view('booking/data-booking', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');

    }

    public function tambahBooking()
    {
        $modelbooking = new ModelBooking();
        $uri = service('uri');
        $db = \Config\Database::connect();
        $id_buku = $uri->getSegment(3);
        $cek = session('id_user');

        if(!$cek) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Harus login terlebih dahulu!</div>');
            return redirect()->back()->withInput();
        }

        // memilih data buku yang untuk dimasukkan ke table temp/keranjang melalui variable $isi
        $d = $db->table('buku')->select('*')->where('id', $id_buku)->get()->getRowArray();

        // berupa data data yang akan disimpan ke dalam tabel temp/keranjang
        $isi = [
            'id_buku' => $id_buku,
            'judul_buku'=> $d['judul_buku'],
            'id_user' => session('id_user'),
            'email_user' => session('email'),
            'tgl_booking' => date('Y-m-d H:i:s'),
            'image' => $d['image'],
            'penulis' => $d['pengarang'],
            'penerbit' => $d['penerbit'],
            'tahun_terbit' => $d['tahun_terbit']
        ];

        // cek apakah buku yang diklik booking sudah ada di keranjang
        $temp = $modelbooking->getDataWhere('temp', ['id_buku' => $id_buku, 'id_user' => session('id_user')])->countAllResults();

        $userid = session('id_user');

        // cek jika sudah memasukan 3 buku untuk dibooking dalam keranjang
        $tempuser = $db->table('temp')->select('*')->where(['id_user' => $userid])->countAllResults();

        $cekpinjam = $db->table('pinjam')->select('id_user')->where(['id_user' => $userid])->countAllResults();

        //cek jika masih ada booking buku yang belum diambil
        $databooking = $db->table('booking')->select('*')->where(['id_user' => $userid])->countAllResults();

        if ($databooking > 0) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Masih Ada booking buku sebelumnya yang belum diambil.<br> Ambil Buku yang dibooking atau tunggu 1x24 Jam untuk bisa booking kembali </div>');
            return redirect()->to(base_url());
        }

        if ($cekpinjam > 0) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Anda tidak boleh meminjam buku sebelum mengembalikan buku yang sedang anda dipinjam!</div>');
            return redirect()->to('home');
        }

        // jika buku yang diklik booking sudah ada dikeranjang
        if ($temp > 0) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Buku ini Sudah anda booking </div>');
            return redirect()->to(base_url());
        }

        //jika buku yang akan dibooking sudah mencapai 3 item(s)
        if ($tempuser == 3) {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Booking Buku Tidak Boleh Lebih dari 3</div>');
            return redirect()->to(base_url('home'));
        }

        // membuat tabel temp jika belum ada
        $modelbooking->createTemp();
        $modelbooking->insertData('temp', $isi);

        // pesan ketika berhasil memasukkan buku ke keranjang
        session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Buku berhasil ditambahkan ke keranjang </div>');
        return redirect()->to(base_url());
    }

    public function hapusBooking()
    {
        $modelbooking = new ModelBooking();
        // $db = \Config\Database::connect();
        $uri = service('uri');

        $id_buku = $uri->getSegment(3);
        $id_user = session('id_user');

        $modelbooking->deleteData(['id_buku' => $id_buku, 'id_user' => $id_user], 'temp');
        $kosong = $this->db->table('temp')->where(['id_user' => $id_user])->countAllResults();

        if ($kosong < 1) {
            session()->setFlashdata('pesan', '<div class="alert alert-massege alert-danger" role="alert">Tidak Ada Buku dikeranjang</div>');
            return redirect()->to(base_url());
        } else {
            return redirect()->to('booking');
        }
    }

    public function bookingSelesai($where)
    {
        $db = \Config\Database::connect();
        $modelbooking = new ModelBooking();
        // mengupdate stok dan dibooking di tabel buku saat proses booking diselesaikan
        // $db->table('buku','temp')->where(['buku.id' => 'temp.id_buku'])->update(['buku.dibooking' => intval('buku.dibooking')+1, 'buku.stok' => intval('buku.stok')-1]);
        $db->query("UPDATE buku, temp SET buku.dibooking=buku.dibooking+1, buku.stok=buku.stok-1 WHERE buku.id=temp.id_buku");

        $tglsekarang = date('Y-m-d');
        $isibooking  = [
            'id_booking' => $modelbooking->kodeOtomatis('booking', 'id_booking'),
            'tgl_booking' => date('Y-m-d H:m:s'),
            'batas_ambil' => date('Y-m-d', strtotime('+2 days', strtotime($tglsekarang))),
            'id_user' => $where
        ];

        //menyimpan ke tabel booking dan detail booking, dan mengosongkan tabel temp
        $modelbooking->insertData('booking', $isibooking);
        $modelbooking->simpanDetail($where); 
        $modelbooking->kosongkanData('temp', ['id_user' => session('id_user')]);

        return redirect()->to('booking/info');
    }

    public function info()
    {
        $modeluser = new ModelUser();
        $db = \Config\Database::connect();

        $where = session('id_user');
        $data = [
            'user' => session('nama'),
            'title' => "Selesai Booking",
            'validation' => \Config\Services::validation(),
            'useraktif' => $modeluser->cekData(['id' => session('id_user')])->getResult(),
            'items' => $db->query("SELECT * FROM booking bo, booking_detail d, buku bu WHERE d.id_booking = bo.id_booking AND d.id_buku = bu.id AND bo.id_user = '$where'")->getResultArray()
        ];

        echo view('templates/templates-user/header', $data);
        echo view('booking/info-booking', $data);
        echo view('templates/templates-user/modal');
        echo view('templates/templates-user/footer');
    }
    
    public function exportToPDF()
    {
        $modeluser = new ModelUser();
        $db = \Config\Database::connect();
        $id_user = session('id_user');
        $data = [
            'user' => session('nama'),
            'judul' => 'Cetak Bukti Booking',
            'useraktif' => $modeluser->cekData(['id' => session('id_user')])->getResultArray(),
            'items' => $db->query("SELECT * FROM booking as bo, booking_detail as d, buku as bu WHERE d.id_booking = bo.id_booking and d.id_buku = bu.id and bo.id_user = '$id_user'")->getResultArray()
        ];

        $dompdf = new Dompdf();

        $paper_size = 'A4';
        $orientation = 'potrait';
        $html = view('booking/bukti-pdf', $data);

        $dompdf->setPaper($paper_size, $orientation);
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("bukti-booking-$id_user.pdf", array('Attachment' => 0)); // nama file pdf


    }
}