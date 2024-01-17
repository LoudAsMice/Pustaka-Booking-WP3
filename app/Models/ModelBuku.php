<?php

namespace App\Models;

use CodeIgniter\Database\SQLite3\Table;
use CodeIgniter\Model;

class ModelBuku extends Model
{
    public function getBuku(){
        return $this->db->table('buku')->get();
    }

    public function getLimitBuku(){
        return $this->db->table('buku')->limit(5)->get();
    }

    public function joinKategoriBuku($where)
    {
        return $this->db->table('kategori')->select()->join('buku', 'buku.id_kategori = kategori.id')->where($where)->get();
    }


    public function bukuWhere($where)
    {
        return $this->db->table('buku')->where($where)->get();
    }

    public function simpanBuku($data = null)
    {
        $this->db->table('buku')->insert($data);
    }

    public function updateBuku($data = null, $where = null)
    {
        $this->db->table('buku')->set($data)->where('id', $where)->update();
    }

    public function hapusBuku($where = null)
    {
        $this->db->table('buku')->delete($where);
    }

    public function total($field, $where)
    {
        $this->db->table('buku')->selectSum($field);
        if(!empty($where) && count($where) > 0){
            $this->db->table('buku')->where($where);
        }
        $this->db->table('buku')->from('buku');
        return $this->db->table('buku')->get()->getRow($field);
    }

    //manajemen kategori
    public function getKategori()
    {
        $db = \Config\Database::connect();
        // return $db->table('kategori')->get();
        return $db->table('kategori')->get();
    }

    public function kategoriWhere($where)
    {
        return $this->db->table('kategori')->getWhere($where);
    }

    public function simpanKategori($data = null)
    {
        $this->db->table('kategori')->insert($data);
    }

    public function hapusKategori($where = null)
    {
        $this->db->table('kategori')->delete($where);
    }

    public function updateKategori($where = null, $data = null)
    {
        $this->db->table('kategori')->update($data, $where);
    }

}