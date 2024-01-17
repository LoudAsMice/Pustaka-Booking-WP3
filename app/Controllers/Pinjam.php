<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelBooking;
use App\Models\ModelPinjam;
use App\Models\ModelUser;

use function PHPUnit\Framework\returnSelf;

class Pinjam extends BaseController
{
    public $modeluser;
    public $modelbooking;
    public $modelpinjam;
    public $db;

    public function __construct()
    {
        $this->modeluser = new ModelUser();
        $this->modelbooking = new ModelBooking();
        $this->modelpinjam = new ModelPinjam();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'judul' => "Data Pinjam",
            'user' => $this->modeluser->cekData(['email' => session('email')])->getRowArray(),
            'pinjam' => $this->modelpinjam->joinData()
        ];

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('pinjam/data-pinjam', $data);
        echo view('templates/footer');
    }

    public function daftarBooking()
    {
        $data = [
            'judul' => "Daftar Booking",
            'user' => $this->modeluser->cekData(['email' => session('email')])->getRowArray(),
            'pinjam' => $this->db->query("SELECT * FROM booking")->getResultArray()
        ];
        
        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('booking/daftar-booking', $data);
        echo view('templates/footer');
    }

    public function bookingDetail()
    {
        $uri = service('uri');
        $id_booking = $uri->getSegment(3);
        $data = [
            'judul' => "Booking Detail",
            'user' => $this->modeluser->cekData(['email' => session('email')])->getRowArray(),
            'agt_booking' => $this->db->query("SELECT* FROM booking b, user u WHERE b.id_user = u.id AND b.id_booking = '$id_booking'")->getResultArray(),
            'detail' => $this->db->query("SELECT id_buku, judul_buku, pengarang, penerbit, tahun_terbit FROM booking_detail d, buku b WHERE d.id_buku = b.id AND d.id_booking = '$id_booking'")->getResultArray()
        ];

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('booking/booking-detail', $data);
        echo view('templates/footer');
    }

    public function pinjamAct()
    {
        $uri = service('uri');
        $id_booking = $uri->getSegment(3);
        $lama = $_POST['lama'];
        $bo = $this->db->query("SELECT * FROM booking WHERE id_booking = '$id_booking'")->getRowArray();
        $tglsekarang = date('Y-m-d');
        $no_pinjam = $this->modelbooking->kodeOtomatis('pinjam', 'no_pinjam');
        $databooking = [
            'no_pinjam' => $no_pinjam,
            'id_booking' => $id_booking,
            'tgl_pinjam' => $tglsekarang,
            'id_user' => $bo['id_user'],
            'tgl_kembali' => date('Y-m-d', strtotime('+' . $lama . ' days', strtotime($tglsekarang))),
            'tgl_pengembalian' => '0000-00-00',
            'status' => 'Pinjam',
            'total_denda' => 0
        ];

        $this->modelpinjam->simpanPinjam($databooking);
        $this->modelpinjam->simpanDetail($id_booking, $no_pinjam);
        $denda = $_POST['denda'];
        $this->db->query("UPDATE detail_pinjam set denda = '$denda'");

        // hapus data booking yang bukunya diambil untuk dipinjam
        $this->modelpinjam->deleteData('booking', ['id_booking' => $id_booking]);
        $this->modelpinjam->deleteData('booking_detail', ['id_booking' => $id_booking]);

        // update dibooking dan dipinjam pada tabel buku saat buku yang dibooking diambil untuk dipinjam
        $this->db->query("UPDATE buku, detail_pinjam SET buku.dipinjam = buku.dipinjam+1, buku.dibooking = buku.dibooking-1 WHERE buku.id = detail_pinjam.id_buku");

        session()->setFlashdata('pesan', '<div class="alert alert-message alert-success" role="alert">Data Peminjaman Berhasil Disimpan</div>');

        return redirect()->to('pinjam');
    }

    public function ubahStatus()
    {
        $uri = service('uri');
        $id_buku = $uri->getSegment(3);
        $no_pinjam = $uri->getSegment(4);
        $where = ['id_buku' => $uri->getSegment(3)];

        $tgl = date('Y-m-d');
        $status = 'Kembali';

        // update status menjadi kembali pada saat buku dikembalikan
        $this->db->query("UPDATE pinjam, detail_pinjam SET pinjam.status = '$status', pinjam.tgl_pengembalian = '$tgl' WHERE detail_pinjam.id_buku = '$id_buku' AND pinjam.no_pinjam = '$no_pinjam'");

        // update stok dan dipinjam pada tabel buku
        $this->db->query("UPDATE buku, detail_pinjam SET buku.dipinjam = buku.dipinjam-1, buku.stok = buku.stok+1 WHERE buku.id = detail_pinjam.id_buku");

        session()->setFlashdata('pesan', '<div class="alert alert-message alert-success" role="alert">Berhasil!</div>');

        return redirect()->to('pinjam');
    }
}