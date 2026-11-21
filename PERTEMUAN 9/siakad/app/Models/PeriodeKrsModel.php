<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodeKrsModel extends Model
{
    protected $table = 'periode_krs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'tahun_ajaran',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];
}