<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MatakuliahModel;
use App\Models\DosenModel;

class Matakuliah extends BaseController
{
    public function index()
    {
        $model = new MatakuliahModel();

        $data['matakuliah'] = $model
            ->select('matakuliah.*, dosen.nama AS nama_dosen')
            ->join('dosen', 'dosen.id = matakuliah.dosen_id', 'left')
            ->findAll();

        return view('admin/matakuliah/index', $data);
    }

    public function create()
    {
        return view('admin/matakuliah/form', [
            'matakuliah' => null,
            'dosen' => (new DosenModel())->findAll()
        ]);
    }

    public function store()
    {
        $rules = [
            'kode' => 'required|is_unique[matakuliah.kode]',
            'nama' => 'required|min_length[3]',
            'sks' => 'required|integer|greater_than[0]|less_than_equal_to[6]',
            'semester' => 'required|integer|greater_than[0]|less_than_equal_to[8]',
            'kuota' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new MatakuliahModel())->insert([
            'dosen_id' => $this->request->getPost('dosen_id') ?: null,
            'kode' => $this->request->getPost('kode'),
            'nama' => $this->request->getPost('nama'),
            'sks' => $this->request->getPost('sks'),
            'semester' => $this->request->getPost('semester'),
            'semester_akademik' => $this->request->getPost('semester_akademik') ?: null,
            'kuota' => $this->request->getPost('kuota'),
            'jadwal' => $this->request->getPost('jadwal')
        ]);

        return redirect()->to('/admin/matakuliah')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        return view('admin/matakuliah/form', [
            'matakuliah' => (new MatakuliahModel())->find($id),
            'dosen' => (new DosenModel())->findAll()
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[3]',
            'sks' => 'required|integer|greater_than[0]|less_than_equal_to[6]',
            'semester' => 'required|integer|greater_than[0]|less_than_equal_to[8]',
            'kuota' => 'required|integer|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new MatakuliahModel())->update($id, [
            'dosen_id' => $this->request->getPost('dosen_id') ?: null,
            'nama' => $this->request->getPost('nama'),
            'sks' => $this->request->getPost('sks'),
            'semester' => $this->request->getPost('semester'),
            'semester_akademik' => $this->request->getPost('semester_akademik') ?: null,
            'kuota' => $this->request->getPost('kuota'),
            'jadwal' => $this->request->getPost('jadwal')
        ]);

        return redirect()->to('/admin/matakuliah')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new MatakuliahModel();

        if (!$model->find($id)) {
            return redirect()->back()->with('error', 'Mata kuliah tidak ditemukan.');
        }

        try {
            $model->delete($id);

            return redirect()->to('/admin/matakuliah')
                ->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', 'Mata kuliah tidak dapat dihapus karena sudah digunakan dalam KRS.');
        }
    }
}