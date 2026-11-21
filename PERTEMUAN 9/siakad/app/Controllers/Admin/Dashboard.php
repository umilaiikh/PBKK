<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'jumlahMahasiswa' => $db->table('mahasiswa')->countAllResults(),
            'jumlahDosen' => $db->table('dosen')->countAllResults(),
            'jumlahMatakuliah' => $db->table('matakuliah')->countAllResults(),
            'jumlahUser' => $db->table('users')->countAllResults(),
        ];

        return view('dashboard/admin', $data);
    }
}