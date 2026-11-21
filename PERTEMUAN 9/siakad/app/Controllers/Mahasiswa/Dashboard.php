<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', session()->get('user_id'))
            ->get()->getRowArray();

        $jumlahKrs = 0;
        $totalSks = 0;
        $jumlahNilai = 0;
        $ipk = 0;

        if ($mahasiswa) {
            $ringkasan = $db->table('krs')
                ->select('COUNT(krs.id) AS jumlah, COALESCE(SUM(matakuliah.sks),0) AS sks')
                ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
                ->where('krs.mahasiswa_id', $mahasiswa['id'])
                ->get()->getRowArray();

            $jumlahKrs = (int) $ringkasan['jumlah'];
            $totalSks = (int) $ringkasan['sks'];

            $nilai = $db->table('krs')
                ->select('SUM(krs.bobot * matakuliah.sks) AS total_bobot, SUM(matakuliah.sks) AS total_sks, COUNT(krs.id) AS jumlah')
                ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
                ->where('krs.mahasiswa_id', $mahasiswa['id'])
                ->where('krs.nilai_angka IS NOT NULL')
                ->get()->getRowArray();

            $jumlahNilai = (int) $nilai['jumlah'];

            if ($nilai['total_sks'] > 0) {
                $ipk = round($nilai['total_bobot'] / $nilai['total_sks'], 2);
            }
        }

        return view('dashboard/mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'jumlahKrs' => $jumlahKrs,
            'totalSks' => $totalSks,
            'jumlahNilai' => $jumlahNilai,
            'ipk' => $ipk
        ]);
    }
}