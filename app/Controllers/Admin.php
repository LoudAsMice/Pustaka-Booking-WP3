<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ModelBuku;
use App\Models\ModelUser;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use function App\Helpers\cek_login;

class Admin extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
    }

    public function index()
    {
        if(session('role_id') == null){
            session()->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Akses ditolak. Anda belum login!! </div>');
            return redirect()->to('autentifikasi/gagal');
            
        } elseif(session('role_id') != 1) {
            return redirect()->to('autentifikasi/blok');
        }

        helper('pustaka_helper');
        $modeluser = new ModelUser();
        $modelbuku = new ModelBuku();
        $data['judul'] = 'Dashboard';
        $data['user'] = $modeluser->cekData(['email' => session()->get('email')])->getRowArray();
        $data['anggota'] = $modeluser->getUserLimit()->get()->getResultArray();
        $data['buku'] = $modelbuku->getLimitBuku()->getResultArray();
        $data['modeluser'] = new ModelUser();
        $data['modelbuku'] = new ModelBuku();
        
        if(!cek_login()){
            echo view('templates/header', $data);
            echo view('templates/sidebar', $data);
            echo view('templates/topbar', $data);
            echo view('admin/index', $data);
            echo view('templates/footer');
        } else {
            return redirect()->to('autentifikasi/gagal');
        }
    }

}