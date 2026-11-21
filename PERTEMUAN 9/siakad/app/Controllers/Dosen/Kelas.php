<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\MatakuliahModel;
use App\Models\KrsModel;
use App\Models\MahasiswaModel;

class Kelas extends BaseController
{
    private function getDosen()
    {
        return (new DosenModel())
            ->where('user_id', session()->get('user_id'))
            ->first();
    }

    public function index()
    {
        $dosen = $this->getDosen();

        if (!$dosen) {
            return redirect()->to('/logout');
        }

        $kelas = (new MatakuliahModel())
            ->where('dosen_id', $dosen['id'])
            ->findAll();

        return view('dosen/kelas/index', [
            'dosen' => $dosen,
            'kelas' => $kelas
        ]);
    }

    public function peserta($id)
    {
        $dosen = $this->getDosen();

        $kelas = (new MatakuliahModel())
            ->where('id', $id)
            ->where('dosen_id', $dosen['id'])
            ->first();

        if (!$kelas) {
            return redirect()->to('/dosen/kelas')
                ->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        }

        $peserta = (new KrsModel())
            ->select('krs.*, mahasiswa.nim, mahasiswa.nama')
            ->join('mahasiswa', 'mahasiswa.id = krs.mahasiswa_id')
            ->where('krs.matakuliah_id', $id)
            ->findAll();

        return view('dosen/kelas/peserta', [
            'kelas' => $kelas,
            'peserta' => $peserta
        ]);
    }

    public function nilai($id)
    {
        $dosen = $this->getDosen();

        $krs = (new KrsModel())
            ->select('krs.*, mahasiswa.nim, mahasiswa.nama, matakuliah.nama AS nama_mk')
            ->join('mahasiswa', 'mahasiswa.id = krs.mahasiswa_id')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where('krs.id', $id)
            ->where('matakuliah.dosen_id', $dosen['id'])
            ->first();

        if (!$krs) {
            return redirect()->back()
                ->with('error', 'Data peserta tidak ditemukan.');
        }

        return view('dosen/kelas/nilai', ['krs' => $krs]);
    }

    public function simpanNilai($id)
    {
        $dosen = $this->getDosen();

        $krs = (new KrsModel())
            ->select('krs.id, matakuliah.dosen_id')
            ->join('matakuliah', 'matakuliah.id = krs.matakuliah_id')
            ->where('krs.id', $id)
            ->where('matakuliah.dosen_id', $dosen['id'])
            ->first();

        if (!$krs) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengubah nilai ini.');
        }

        $nilai = (float) $this->request->getPost('nilai_angka');

        if ($nilai < 0 || $nilai > 100) {
            return redirect()->back()
                ->with('error', 'Nilai harus berada di antara 0 sampai 100.');
        }

        if ($nilai >= 86) {
            $huruf = 'A';
            $bobot = 4.0;
        } elseif ($nilai >= 76) {
            $huruf = 'AB';
            $bobot = 3.5;
        } elseif ($nilai >= 66) {
            $huruf = 'B';
            $bobot = 3.0;
        } elseif ($nilai >= 61) {
            $huruf = 'BC';
            $bobot = 2.5;
        } elseif ($nilai >= 56) {
            $huruf = 'C';
            $bobot = 2.0;
        } elseif ($nilai >= 41) {
            $huruf = 'D';
            $bobot = 1.0;
        } else {
            $huruf = 'E';
            $bobot = 0.0;
        }

        (new KrsModel())->update($id, [
            'nilai_angka' => $nilai,
            'nilai_huruf' => $huruf,
            'bobot' => $bobot
        ]);

        return redirect()->to('/dosen/kelas/peserta/' .
            $this->request->getPost('matakuliah_id'))
            ->with('success', "Nilai berhasil disimpan: $huruf");
    }

    public function bimbingan()
    {
        $dosen = $this->getDosen();

        $mahasiswa = (new MahasiswaModel())
            ->where('dosen_wali_id', $dosen['id'])
            ->findAll();

        return view('dosen/bimbingan', [
            'mahasiswa' => $mahasiswa
        ]);
    }
}