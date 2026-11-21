<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\MatakuliahModel;
use App\Models\KrsModel;
use App\Models\PeriodeKrsModel;

class Akademik extends BaseController
{
    private function getMahasiswa()
    {
        return (new MahasiswaModel())
            ->where('user_id', session()->get('user_id'))
            ->first();
    }

    public function krs()
    {
        $mhs = $this->getMahasiswa();

        if (!$mhs) {
            return redirect()->to('/logout');
        }

        $periode = (new PeriodeKrsModel())
            ->where('status', 'Dibuka')
            ->first();

        $dataKrs = [];

        if ($periode) {
            $dataKrs = (new KrsModel())
                ->select('krs.*, matakuliah.kode, matakuliah.nama, matakuliah.sks, matakuliah.jadwal')
                ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
                ->where('krs.mahasiswa_id', $mhs['id'])
                ->where('krs.tahun_ajaran', $periode['tahun_ajaran'])
                ->where('krs.semester', $periode['semester'])
                ->findAll();
        }

        $matakuliah = (new MatakuliahModel())->findAll();

        return view('mahasiswa/krs', [
            'mhs' => $mhs,
            'periode' => $periode,
            'dataKrs' => $dataKrs,
            'matakuliah' => $matakuliah
        ]);
    }

    public function tambahKrs()
    {
        $mhs = $this->getMahasiswa();

        $periode = (new PeriodeKrsModel())
            ->where('status', 'Dibuka')
            ->first();

        if (!$periode) {
            return redirect()->back()
                ->with('error', 'Periode KRS sedang ditutup.');
        }

        $hariIni = date('Y-m-d');

        if (
            $hariIni < $periode['tanggal_mulai'] ||
            $hariIni > $periode['tanggal_selesai']
        ) {
            return redirect()->back()
                ->with('error', 'Pengisian KRS di luar tanggal periode.');
        }

        $mkId = (int) $this->request->getPost('matakuliah_id');

        $mkModel = new MatakuliahModel();
        $mk = $mkModel->find($mkId);

        if (!$mk) {
            return redirect()->back()
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $krsModel = new KrsModel();

        $sudahAda = $krsModel
            ->where('mahasiswa_id', $mhs['id'])
            ->where('matakuliah_id', $mkId)
            ->where('tahun_ajaran', $periode['tahun_ajaran'])
            ->first();

        if ($sudahAda) {
            return redirect()->back()
                ->with('error', 'Mata kuliah tersebut sudah ada di KRS.');
        }

        // Tidak boleh mengambil ulang mata kuliah yang sudah lulus.
        $pernahLulus = $krsModel
            ->where('mahasiswa_id', $mhs['id'])
            ->where('matakuliah_id', $mkId)
            ->whereIn('nilai_huruf', ['A', 'AB', 'B', 'BC', 'C'])
            ->first();

        if ($pernahLulus) {
            return redirect()->back()
                ->with('error', 'Mata kuliah tersebut sudah pernah lulus.');
        }

        // Periksa kuota kelas.
        $jumlahPeserta = $krsModel
            ->where('matakuliah_id', $mkId)
            ->where('tahun_ajaran', $periode['tahun_ajaran'])
            ->where('semester', $periode['semester'])
            ->countAllResults();

        if ($jumlahPeserta >= $mk['kuota']) {
            return redirect()->back()
                ->with('error', 'Kuota kelas sudah penuh.');
        }

        // Maksimal 24 SKS.
        $krsSaatIni = $krsModel
            ->select('matakuliah.sks')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where('krs.mahasiswa_id', $mhs['id'])
            ->where('krs.tahun_ajaran', $periode['tahun_ajaran'])
            ->where('krs.semester', $periode['semester'])
            ->findAll();

        $totalSks = 0;

        foreach ($krsSaatIni as $item) {
            $totalSks += (int) $item['sks'];
        }

        if ($totalSks + (int) $mk['sks'] > 24) {
            return redirect()->back()
                ->with('error', 'Total KRS tidak boleh melebihi 24 SKS.');
        }

        $krsModel->insert([
            'mahasiswa_id' => $mhs['id'],
            'matakuliah_id' => $mkId,
            'tahun_ajaran' => $periode['tahun_ajaran'],
            'semester' => $periode['semester']
        ]);

        return redirect()->to('/mahasiswa/krs')
            ->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
    }

    public function hapusKrs($id)
    {
        $mhs = $this->getMahasiswa();

        $krsModel = new KrsModel();

        $krs = $krsModel
            ->where('id', $id)
            ->where('mahasiswa_id', $mhs['id'])
            ->first();

        if (!$krs) {
            return redirect()->back()
                ->with('error', 'Data KRS tidak ditemukan.');
        }

        if ($krs['nilai_angka'] !== null) {
            return redirect()->back()
                ->with('error', 'KRS tidak dapat dihapus karena nilai sudah diinput.');
        }

        $periode = (new PeriodeKrsModel())
            ->where('status', 'Dibuka')
            ->first();

        if (
            !$periode ||
            $krs['tahun_ajaran'] !== $periode['tahun_ajaran'] ||
            $krs['semester'] !== $periode['semester']
        ) {
            return redirect()->back()
                ->with('error', 'Periode perubahan KRS sudah ditutup.');
        }

        $krsModel->delete($id);

        return redirect()->to('/mahasiswa/krs')
            ->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
    }

    public function khs()
    {
        $mhs = $this->getMahasiswa();

        $hasil = (new KrsModel())
            ->select('krs.*, matakuliah.kode, matakuliah.nama, matakuliah.sks')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where('krs.mahasiswa_id', $mhs['id'])
            ->where('krs.nilai_angka IS NOT NULL')
            ->orderBy('krs.tahun_ajaran', 'ASC')
            ->findAll();

        $semesterList = [];

        foreach ($hasil as $item) {
            $key = $item['tahun_ajaran'] . ' - ' . $item['semester'];

            if (!isset($semesterList[$key])) {
                $semesterList[$key] = [
                    'items' => [],
                    'total_sks' => 0,
                    'total_bobot' => 0,
                    'ip' => 0
                ];
            }

            $semesterList[$key]['items'][] = $item;
            $semesterList[$key]['total_sks'] += (int) $item['sks'];
            $semesterList[$key]['total_bobot'] +=
                (float) $item['bobot'] * (int) $item['sks'];
        }

        foreach ($semesterList as &$semester) {
            if ($semester['total_sks'] > 0) {
                $semester['ip'] = round(
                    $semester['total_bobot'] / $semester['total_sks'],
                    2
                );
            }
        }

        return view('mahasiswa/khs', [
            'mhs' => $mhs,
            'semesterList' => $semesterList
        ]);
    }

    public function transkrip()
    {
        $mhs = $this->getMahasiswa();

        $nilai = (new KrsModel())
            ->select('krs.*, matakuliah.kode, matakuliah.nama, matakuliah.sks')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where('krs.mahasiswa_id', $mhs['id'])
            ->where('krs.nilai_angka IS NOT NULL')
            ->orderBy('matakuliah.kode', 'ASC')
            ->findAll();

        $totalSks = 0;
        $totalBobot = 0;

        foreach ($nilai as $item) {
            $totalSks += (int) $item['sks'];
            $totalBobot += (float) $item['bobot'] * (int) $item['sks'];
        }

        $ipk = $totalSks > 0
            ? round($totalBobot / $totalSks, 2)
            : 0;

        return view('mahasiswa/transkrip', [
            'mhs' => $mhs,
            'nilai' => $nilai,
            'totalSks' => $totalSks,
            'ipk' => $ipk
        ]);
    }
}