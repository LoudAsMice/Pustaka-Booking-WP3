<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
    public function cekData($data){
        return $this->db->table('user')->getWhere($data);
    }

    public function simpanData($data){
        return $this->db->table('user')->insert($data);
    }

    public function getUserWhere($where = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        return $builder->where($where);
    }

    public function cekUserAccess($where = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        $builder->select('*');
        $builder->from('access_menu');
        $builder->where($where);
        return $builder->get();
    }

    public function getUserLimit()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user');
        return $builder->limit(10,0);
    }
}