<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPinjam extends Model
{
    public function simpanPinjam($data)
    {
        return $this->db->table('pinjam')->insert($data);
    }

    public function selectData($table, $where)
    {
        return $this->db->table($table)->where($where)->get();
    }

    public function updateData($data, $where)
    {
        return $this->db->table('pinjam')->where($where)->update($data);
    }

    public function deleteData($table, $where)
    {
        return $this->db->table($table)->where($where)->delete();
    }

    public function joinData()
    {
        return $this->db->table('pinjam')->select('*')->join('detail_pinjam', 'detail_pinjam.no_pinjam = pinjam.no_pinjam', 'Left')->get()->getResultArray();
    }

    public function simpanDetail($idbooking, $nopinjam)
    {
        $sql = "INSERT INTO detail_pinjam (no_pinjam, id_buku) SELECT pinjam.no_pinjam, booking_detail.id_buku FROM pinjam, booking_detail WHERE booking_detail.id_booking = $idbooking AND pinjam.no_pinjam = '$nopinjam'";
        return $this->db->query($sql);
    }
}