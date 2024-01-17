<?php

namespace App\Controllers;

use App\Models\ModelUser;

class Member extends BaseController
{
    public function index()
    {
        return $this->_login();
    }

    private function _login()
    {
        $modeluser = new ModelUser();
        $email = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];

        $user = $modeluser->cekData(['email' => $email])->getRowArray();

        // jika usernya ada
        if ($user) {
            // jika user sudah aktif
            if ($user['is_active'] == 1) {
                // cek password
                if (md5($password) == $user['password']) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'id_user' => $user['id'],
                        'nama' => $user['nama']
                    ];

                    session()->set($data);
                    session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Berhasil Login!</div>');
                    return redirect()->back()->withInput();
                } else {
                    session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Password salah!!</div>');
                    return redirect()->to('home');
                }
            } else {
                session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">User belum diaktifasi!!</div>');
                return redirect()->to('home');
            }
        } else {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Email tidak terdaftar!!</div>');
            return redirect()->to('home');
        }
    }

    public function daftar()
    {
        $modeluser = new ModelUser();
        $rules = [
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|trim|valid_email|is_unique[user.email]',
            'password1' => 'required|trim|min_length[3]',
            'password2' => 'required|trim|matches[password1]'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama harus diisi!'
            ],
            'alamat' => [
                'required' => 'Alamat harus diisi!'
            ],
            'email' => [
                'required' => 'Email harus diisi!',
                'valid_email' => 'Email tidak benar!',
                'is_unique' => 'Email sudah terdaftar!'
            ],
            'password1' => [
                'required' => 'Password harus diisi!',
                'min_length' => 'Password terlalu pendek!'
            ],
            'password2' => [
                'required' => 'Konfirmasi password harus diisi!',
                'matches' => 'Konfirmasi password tidak benar!'
            ]
        ];
        if($this->validate($rules, $messages)){
            $email = $_POST['email'];
            $data = [
                'nama' => htmlspecialchars($_POST['nama']),
                'alamat' => $_POST['alamat'],
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => md5($_POST['password1']),
                'role_id' => 2,
                'is_active' => 0,
                'tanggal_input' => time()
            ];

            $modeluser->simpanData($data);

            session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Selamat! akun anda sudah dibuat.</div>');
            return redirect()->back()->withInput();
        } else {
            session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Gagal membuat akun!</div>');
            return redirect()->back()->withInput();
        }
    }
    
    public function myProfile()
    {
        $modeluser = new ModelUser();
        $user = $modeluser->cekData(['email' => session('email')])->getRowArray();

        $data = [
            'image' => $user['image'],
            'user' => $user['nama'],
            'email' => $user['email'],
            'tanggal_input' => $user['tanggal_input'],
            'title' => 'Profil Saya',
            'validation' => \Config\Services::validation()
        ];
        // print_r($data);
        echo view('templates/templates-user/header', $data);
        echo view('member/index', $data);
        echo view('templates/templates-user/modal', $data);
        echo view('templates/templates-user/footer');
    }

    public function ubahProfil()
    {
        $modeluser = new ModelUser();
        $db = \Config\Database::connect();
        $user = $modeluser->cekData(['email' => session('email')])->getRowArray();
        $data = [
            'image' => $user['image'],
            'user' => $user['nama'],
            'email' => $user['email'],
            'tanggal_input' => $user['tanggal_input'],
            'title' => 'Profil Saya',
            'validation' => \Config\Services::validation()
        ];

        $rules = [
            'nama' => 'required'
        ];

        $messages = [
            'nama' => [
                'required' => 'Nama tidak boleh kosong!'
            ],
        ];

        $validationRule = [
            'image' => [
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
            ],
        ];

        if(!$this->validate($rules, $messages)) {
            echo view('templates/templates-user/header', $data);
            echo view('member/ubah-anggota', $data);
            echo view('templates/templates-user/modal', $data);
            echo view('templates/templates-user/footer');
        } else {
            $nama = $_POST['nama'];
            $email = $_POST['email'];

            //jika ada gambar yang akan diupload
            
            
            if($this->validate($validationRule)){
                $img = $this->request->getFile('image');
                $old = $user['image'];
                $r = $img->getRandomName();
                if($old != 'default.jpg'){
                    unlink('assets/img/profile'. '/' . $old);
                }
                $img->move('./assets/img/profile/', $r);
                $db->table('user')->set('image', $r)->where(['email' => session('email')])->update();

            }
            $db->table('user')->set('nama', $nama)->where(['email' => session('email')])->update();
            session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Profil Berhasil diubah </div>');
            return redirect()->to('member/myprofile')->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();

        session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Anda telah logout!</div>');
        return redirect()->to('home')->withInput();
    }
}
