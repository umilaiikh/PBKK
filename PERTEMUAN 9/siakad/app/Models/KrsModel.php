<?php

namespace App\Models;

use CodeIgniter\Model;

class KrsModel extends Model
{
    protected $table = 'krs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'mahasiswa_id',
        'matakuliah_id',
        'tahun_ajaran',
        'semester',
        'nilai_angka',
        'nilai_huruf',
        'bobot'
    ];
}