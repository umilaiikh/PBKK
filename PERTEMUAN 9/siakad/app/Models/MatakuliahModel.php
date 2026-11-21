<?php

namespace App\Models;

use CodeIgniter\Model;

class MatakuliahModel extends Model
{
    protected $table = 'matakuliah';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'dosen_id',
        'kode',
        'nama',
        'sks',
        'semester',
        'semester_akademik',
        'kuota',
        'jadwal'
    ];
}