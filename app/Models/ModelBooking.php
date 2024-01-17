<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBooking extends Model
{
    public function getData($table)
    {
        return $this->db->table($table)->get()->getRow();
    }

    public function getDataWhere($table, $where)
    {
        return $this->db->table($table)->where($where);
    }

    public function getOrderByLimit($table, $order, $limit)
    {
        return $this->db->table($table)->orderBy($order, 'desc')->limit($limit)->get();
    }

    public function joinOrder($where)
    {
        return $this->db->table('booking')->select('*')->join('booking_detail', 'booking_detail.id_booking = booking.id_booking')->where($where)->get();
    }

    public function simpanDetail($where = null)
    {
        $sql = "INSERT INTO booking_detail (id_booking, id_buku) SELECT booking.id_booking, temp.id_buku FROM booking, temp WHERE temp.id_user = booking.id_user AND booking.id_user = '$where'";

        return $this->db->query($sql);
    }

    public function insertData($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    public function updateData($table, $data, $where)
    {
        return $this->db->table($table)->where($where)->update($data);
    }

    public function deleteData($where, $table)
    {
        return $this->db->table($table)->where($where)->delete();
    }

    public function cari($table, $where)
    {
        //query mencari record berdasarkan ID-nya
        return $this->db->table($table)->where($where)->get();
    }

    public function kosongkanData($table, $where)
    {
        return $this->db->table($table)->where($where)->delete();
    }

    public function createTemp()
    {
        $sql = "CREATE TABLE IF NOT EXISTS temp(id_booking varchar(12), tgl_booking DATETIME, email_user varchar(128), id_buku int(10))";

        return $this->db->query($sql);
    }

    public function selectJoin()
    {
        $this->db->table('booking')->select('*')->join('booking_detail', 'booking_detail.id_booking = booking.id_booking')->join('buku', 'booking_detail.id_buku = buku.id')->get();
    }

    public function showTemp($where)
    {
        return $this->db->table('temp')->where($where);
    }

    public function kodeOtomatis($table, $key)
    {
        $query = $this->db->table($table)->select('right(' . $key . ',3) as kode', false)->orderBy($key, 'desc')->limit(1);
        if ($query->countAllResults() <> 0) {
            $data = $query->get()->getResult();
            print_r($data);
            $kode = intval($data) + 1;
        } else {
            $kode = 1;
        }

        $kodemax = str_pad($kode, 3, "0", STR_PAD_LEFT);
        $kodejadi = date('dmY') . $kodemax;
        return $kodejadi;
    }
}