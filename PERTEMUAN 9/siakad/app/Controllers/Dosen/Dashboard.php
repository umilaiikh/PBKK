<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $dosen = $db->table('dosen')
            ->where('user_id', session()->get('user_id'))
            ->get()->getRowArray();

        $jumlahMatakuliah = 0;
        $jumlahPeserta = 0;
        $jumlahBimbingan = 0;
        $matakuliah = [];

        if ($dosen) {
            $jumlahMatakuliah = $db->table('matakuliah')
                ->where('dosen_id', $dosen['id'])
                ->countAllResults();

            $jumlahPeserta = $db->table('krs')
                ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
                ->where('matakuliah.dosen_id', $dosen['id'])
                ->countAllResults();

            $jumlahBimbingan = $db->table('mahasiswa')
                ->where('dosen_wali_id', $dosen['id'])
                ->countAllResults();

            $matakuliah = $db->table('matakuliah')
                ->where('dosen_id', $dosen['id'])
                ->get()->getResultArray();
        }

        return view('dashboard/dosen', [
            'dosen' => $dosen,
            'jumlahMatakuliah' => $jumlahMatakuliah,
            'jumlahPeserta' => $jumlahPeserta,
            'jumlahBimbingan' => $jumlahBimbingan,
            'matakuliah' => $matakuliah
        ]);
    }
}