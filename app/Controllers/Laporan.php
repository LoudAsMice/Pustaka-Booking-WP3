<?php

namespace App\Controllers;

use App\Models\ModelBuku;
use App\Models\ModelUser;
use Dompdf\Dompdf;

class Laporan extends BaseController
{
    public $modeluser;
    public $modelbuku;

    public function __construct()
    {
        $this->modeluser = new ModelUser();
        $this->modelbuku = new ModelBuku(); 
    }

    public function laporan_buku()
    {
        $data = [
            'judul' => 'Laporan Data Buku',
            'user' => $this->modeluser->cekData(['email' => session('email')])->getRowArray(),
            'buku' => $this->modelbuku->getBuku()->getResultArray(),
            'kategori' => $this->modelbuku->getKategori()->getResultArray()
        ];

        echo view('templates/header', $data);
        echo view('templates/sidebar', $data);
        echo view('templates/topbar', $data);
        echo view('buku/laporan_buku', $data);
        echo view('templates/footer');
    }

    public function cetak_laporan_buku()
    {
        $data = [
            'buku' => $this->modelbuku->getBuku()->getResultArray(),
            'kategori' => $this->modelbuku->getKategori()->getResultArray()
        ];

        echo view('buku/laporan_print_buku', $data);
    }

    public function laporan_buku_pdf()
    {
        $data['buku'] = $this->modelbuku->getBuku()->getResultArray();

        $dompdf = new Dompdf();
        $html = view('buku/laporan_pdf_buku', $data);
        $paper_size = 'A4';
        $orientation = 'landscape';

        $dompdf->setPaper($paper_size, $orientation);

        //Convert to PDF
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("laporan_data_buku.pdf", array('Attachment' => 0)); // Nama file yg dihasilkan
    }

    public function export_excel()
    {
        $data = array('title' => 'Laporan Buku', 'buku' => $this->modelbuku->getBuku()->getResultArray());
        echo view('buku/export_excel_buku', $data);
    }

    public function laporan_pinjam()
    {
        $data = [
            'judul' => 'Laporan Data Peminjaman',
            'user' => $this->modeluser->cekData(['email' => session('email')])->getRowArray()
        ];
    }
}