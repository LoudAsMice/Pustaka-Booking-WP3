<?php

namespace App\Helpers;

function cek_login()
{
    if(!session()->has('email')){
        session()->setFlashdata('pesan', '<div class="alert alert-danger" role="alert">Akses ditolak. Anda belum login!! </div>'); 
        return redirect()->to('autentifikasi/gagal');
    }
}