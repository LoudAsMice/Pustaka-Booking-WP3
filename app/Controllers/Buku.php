<?php

namespace App\Controllers;

use App\Models\ModelBuku;
use App\Models\ModelUser;
class Buku extends BaseController
{
    public function index()
    {
        $modeluser = new ModelUser();
        $modelbuku = new ModelBuku();
        $data['judul'] = 'Data Buku';
        $data['user'] = $modeluser->cekData(['email' => session('email')])->getRowArray();
        $data['buku'] = $modelbuku->getBuku()->getResultArray();
        $data['kategori'] = $modelbuku->getKategori()->getResultArray();
        $data['validation'] = \Config\Services::validation();

        $rules = [
            'judul_buku' => 'required|min_length[3]',
            'id' => 'required',
            'pengarang' => 'required|min_length[3]',
            'penerbit' => 'required|min_length[3]',
            'tahun_terbit' => 'required|min_length[3]|max_length[4]|numeric',
            'isbn' => 'required|min_length[3]|numeric',
            'stok' => 'required|numeric'
        ];  

        $messages = [
            'judul_buku' => [
                'required' => 'Judul buku harus diisi',
                'min_length' => 'Judul buku terlalu pendek'
            ],
            'id' => [
                'required' => 'Kategori harus diisi',
            ],
            'pengarang' => [
                'required' => 'Nama pengarang harus diisi',
                'min_length' => 'Nama pengarang terlalu pendek'
            ],
            'penerbit' => [
                'required' => 'Nama penerbit harus diisi',
                'min_length' => 'Nama penerbit terlalu pendek'
            ],
            'tahun_terbit' => [
                'required' => 'Tahun terbit harus diisi',
                'min_length' => 'Tahun terbit terlalu pendek',
                'max_length' => 'Tahun terbit terlalu panjang',
                'numeric' => 'Hanya boleh diisi angka'
            ],
            'isbn' => [
                'required' => 'Nomor ISBN harus diisi',
                'min_length' => 'Nomor ISBN terlalu pendek',
                'numeric' => 'Hanya boleh diisi angka'
            ],
            'stok' => [
                'required' => 'Stok harus diisi',
                'numeric' => 'Hanya boleh diisi angka'
            ]
        ];

        $validationRule = [
            'image' => [
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
            ],
        ];

        if(!$this->validate($rules, $messages)){
            echo view('templates/header', $data);
            echo view('templates/sidebar', $data);
            echo view('templates/topbar', $data);
            echo view('buku/index', $data);
            echo view('templates/footer');
        } else {
            $image = $this->request->getFile('image');
            $r = $image->getRandomName();
            $data = [
                'judul_buku' => $_POST['judul_buku'],
                'id' => $_POST['id'],
                'pengarang' => $_POST['pengarang'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'isbn' => $_POST['isbn'],
                'stok' => $_POST['stok'],
                'dipinjam' => 0,
                'dibooking' => 0,
            ];
            if($this->validate($validationRule)){
                if($image){
                    $data = [
                        'judul_buku' => $_POST['judul_buku'],
                        'id' => $_POST['id'],
                        'pengarang' => $_POST['pengarang'],
                        'penerbit' => $_POST['penerbit'],
                        'tahun_terbit' => $_POST['tahun_terbit'],
                        'isbn' => $_POST['isbn'],
                        'stok' => $_POST['stok'],
                        'dipinjam' => 0,
                        'dibooking' => 0,
                        'image' => $r
                    ];
                    $modelbuku->simpanBuku($data);
                    $image->move('./assets/img/uploads/', $r);
                    return redirect()->to('buku');
                }
            } else {
                $modelbuku->simpanBuku($data);
            }
        }

    }

    public function kategori()
    {
        $modeluser = new ModelUser();
        $modelbuku = new ModelBuku();
        $data['judul'] = 'Kategori Buku';
        $data['user'] = $modeluser->cekData(['email' => session('email')])->getRowArray();
        $data['kategori'] = $modelbuku->getKategori()->getResultArray();
        $data['validation'] = \Config\Services::validation();

        $rules = [
            'kategori' => 'required'
        ];

        $messages = [
            'kategori' => [
                'required' => 'Kategori Buku harus diisi'
            ]
        ];


        if(!$this->validate($rules, $messages)){
            echo view('templates/header', $data);
            echo view('templates/sidebar', $data);
            echo view('templates/topbar', $data);
            echo view('buku/kategori', $data);
            echo view('templates/footer');
        } else {
            $data = [
                'kategori' => $_POST['kategori']
            ];
            $modelbuku->simpanKategori($data);
            session()->setFlashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Kategori berhasil ditambah</div>');
            return redirect()->to('buku/kategori');
        }
    }

    public function hapuskategori()
    {
        $modelbuku = new ModelBuku();
        $uri = service('uri');
        $where = ['id' => $uri->getSegment(3)];
        // var_dump($uri->getSegment(3));
        if(!    $modelbuku->hapusKategori($where)){
            session()->setFlashdata('pesan', '<div class="alert alert-info alert-message" role="alert">Kategori berhasil dihapus</div>');
            return redirect()->to('buku/kategori');
        }
    }

    public function ubahbuku()
    {
        $modeluser = new ModelUser();
        $modelbuku = new ModelBuku();
        $uri = service('uri');
        $data['judul'] = 'Ubah Data Buku';
        $data['user'] = $modeluser->cekData(['email' => session('email')])->getRowArray();
        $data['buku'] = $modelbuku->bukuWhere(['id' => $uri->getSegment(3)])->getRowArray();
        $db = \Config\Database::connect();
        $data['validation'] = \Config\Services::validation();
        $data['uri'] = service('uri');
        $buku = $modelbuku->bukuWhere(['id' => $uri->getSegment(3)])->getRowArray();

        $kategori = $modelbuku->joinKategoriBuku(['buku.id' => $uri->getSegment(3)])->getResultArray();
        foreach ($kategori as $k) {
            $data['id'] = $k['id'];
            $data['k'] = $k['kategori'];
        }
        $data['kategori'] = $modelbuku->getKategori()->getResultArray();

        $rules = [
            'judul_buku' => 'required|min_length[3]',
            'id' => 'required',
            'pengarang' => 'required|min_length[3]',
            'penerbit' => 'required|min_length[3]',
            'tahun_terbit' => 'required|min_length[3]|max_length[4]|numeric',
            'isbn' => 'required|min_length[3]|numeric',
            'stok' => 'required|numeric'
        ];

        $messages = [
            'judul_buku' => [
                'required' => 'Judul buku harus diisi',
                'min_length' => 'Judul buku terlalu pendek'
            ],
            'id' => [
                'required' => 'Kategori harus diisi',
            ],
            'pengarang' => [
                'required' => 'Nama pengarang harus diisi',
                'min_length' => 'Nama pengarang terlalu pendek'
            ],
            'penerbit' => [
                'required' => 'Nama penerbit harus diisi',
                'min_length' => 'Nama penerbit terlalu pendek'
            ],
            'tahun_terbit' => [
                'required' => 'Tahun terbit harus diisi',
                'min_length' => 'Tahun terbit terlalu pendek',
                'max_length' => 'Tahun terbit terlalu panjang',
                'numeric' => 'Hanya boleh diisi angka'
            ],
            'isbn' => [
                'required' => 'Nomor ISBN harus diisi',
                'min_length' => 'Nomor ISBN terlalu pendek',
                'numeric' => 'Hanya boleh diisi angka'
            ],
            'stok' => [
                'required' => 'Stok harus diisi',
                'numeric' => 'Hanya boleh diisi angka'
            ]
        ];
        $validationRule = [
            'image' => [
                'rules' => 'uploaded[image]'
                    . '|is_image[image]'
                    . '|mime_in[image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
            ],
        ];

        

        if(!$this->validate($rules, $messages)){
            echo view('templates/header', $data);
            echo view('templates/sidebar', $data);
            echo view('templates/topbar', $data);
            echo view('buku/ubahbuku', $data);
            echo view('templates/footer');
        } else {
            $img = $this->request->getFile('image');
            $old = $buku['image'];
            $r = $img->getRandomName();
            $data = [
                'judul_buku' => $_POST['judul_buku'],
                'id' => $_POST['id'],
                'pengarang' => $_POST['pengarang'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'isbn' => $_POST['isbn'],
                'stok' => $_POST['stok']
            ];
            $modelbuku->updateBuku($data, $buku['id']);
            if($this->validate($validationRule)) {
                if($this->request->getFile('image')){
                    if($old != 'book-default-cover.jpg'){
                        unlink('assets/img/uploads'.'/'.$old);
                    }
                    $db->table('buku')->set('image', $r)->where('id', $uri->getSegment(3))->update();
                    $img->move('./assets/img/uploads/', $r);
                    session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Berhasil Diubah </div>');
                    return redirect()->to('buku');
                }
            }elseif($img == ''){  
                session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Berhasil Diubah </div>');
                return redirect()->to('buku');
            }else{
                session()->setFlashdata('pesan', '<div class="alert alert-danger alert-message" role="alert">Format tidak sesuai! </div>');
                return redirect()->to('buku');
            }
        }
    }

    public function hapusbuku()
    {
        $modelbuku = new ModelBuku();
        $uri = service('uri');
        $loc = $modelbuku->bukuWhere(['id' => $uri->getSegment(3)])->getRowArray();
        unlink('assets/img/uploads'.'/'.$loc['image']);
        $modelbuku->hapusBuku(['id' => $uri->getSegment(3)]);
        session()->setFlashdata('pesan', '<div class="alert alert-success alert-message" role="alert">Buku Berhasil Dihapus </div>');
        return redirect()->to('buku');
    }
}