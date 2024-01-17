<?php


namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ModelUser;

class Autentifikasi extends BaseController
{
    public function index(){
        if(session('email')){
            return redirect()->to('user');
        }
        //jika statusnya sudah login, maka tidak bisa mengakses
        //halaman login alias dikembalikan ke tampilan user

        if (!$this->request->getPost()){

            $data = [
                'judul' => 'Login',
                'user' => '',
                'validation' => \Config\Services::validation()
            ];
            echo view('templates/aute_header', $data);
            echo view('autentifikasi/login', $data);
            echo view('templates/aute_footer');
        }else{
            // $a = md5('123');
            // var_dump($a);
            return $this->_login();
        }
    }
    private function _login(){
        $modeluser = new ModelUser();
        $email = htmlspecialchars($_POST['email']);
        $password = md5($_POST['password']);
        $user = $modeluser->cekData(['email' => $email])->getRowArray();

        //jika usernya ada
        if($user) {
            //jika user sudah aktif
            if ($user['is_active'] == 1){
                //cek password
                if($password == $user['password']) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    
                    session()->set($data);

                    if($user['role_id'] == 1) {
                        return redirect()->to('admin');
                    } else {
                        if ($user['image'] == 'default.jpg') {
                            session()->setFlashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Silahkan ubah profile anda untuk ubah foto profil</div>');
                        }
                        return redirect()->to('user');
                    }
                } else {
                    session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!</div>');
                    return redirect()->back()->withInput();
                }
            } else {
                session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!</div>');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!</div>');
            
            return redirect()->back()->withInput();
        }
    }

    public function cek()
    {
        $rules=[
            'email' => 'required|trim|valid_email',
            'password' => 'required|trim|' 
        ]; 
        
        $messages=[
            'email' => [
                'required' => 'Email Harus diisi!',
                'valid_email' => 'Email Tidak Benar!'
            ], 
            'password' =>[
                'required' => 'Password Harus diisi',
            ]
        ];
        if(!$this->validate($rules,$messages)){
            return redirect()->to('autentifikasi')->withInput();
        } else {
            return $this->_login();
        }
    }

    public function logout()
    {
        session()->remove(['email', 'role_id']);
        return redirect()->to('autentifikasi');
    }

    public function blok()
    {
        echo view('autentifikasi/blok');
    }

    public function gagal()
    {
        echo view('autentifikasi/gagal');
    }

    public function registrasi()
    {
        
        if(!$this->request->getPost()){
            $data = [
                'judul' => 'Registrasi Member',
                'validation' => \Config\Services::validation()
            ];
            echo view('templates/aute_header', $data);
            echo view('autentifikasi/registrasi', $data);
            echo view('templates/aute_footer');
        } 
    }

    public function cekreg()
    {
        $modeluser = new ModelUser();
        $rules = [
            'nama' => 'required',
            'email' => 'required|trim|valid_email|is_unique[user.email]',
            'password1' => 'required|trim|min_length[3]|matches[password2]',
            'password2' => 'required|trim|matches[password1]'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama Harus Diisi'
            ],
            'email' => [
                'required' => 'Email Harus Diisi',
                'valid_email' => 'Email Tidak Benar',
                'is_unique' => 'Email Sudah Terdaftar'
            ],
            'password1' => [
                'required' => 'Password Harus Diisi',
                'matches' => 'Password Tidak Sama!',
                'min_length' => 'Password Terlalu Pendek'
            ],
            'password2' => [
                'required' => 'Konfirmasi Password Harus Diisi'
            ]
        ];

        if(!$this->validate($rules,$messages)){
            $validation = \Config\Services::validation();
            return redirect()->to('autentifikasi/registrasi')->withInput()->with('validation', $validation);
        } else {
            $data = [
                'nama' => htmlspecialchars($_POST['nama']),
                'email' => htmlspecialchars($_POST['email']),
                'image' => 'default.jpg',
                'password' => md5($_POST['password1']),
                'role_id' => 2,
                'is_active' => 0,
                'tanggal_input' => time()
            ];
            $modeluser->simpanData($data);

            session()->setFlashdata('pesan', '<div class="alert alert-success alert-messages" role="alert">Selamat!! akun member anda sudah dibuat. Silahkan Aktivasi Akun anda</div>');
            return redirect()->to('autentifikasi');
        }
        
    }
}