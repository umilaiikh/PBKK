<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\DosenModel;

class Mahasiswa extends BaseController
{
    protected $mahasiswa;
    protected $dosen;

    public function __construct()
    {
        $this->mahasiswa = new MahasiswaModel();
        $this->dosen = new DosenModel();
    }

    public function index()
    {
        $data['mahasiswa'] = $this->mahasiswa
            ->select('mahasiswa.*, dosen.nama AS nama_dosen')
            ->join('dosen', 'dosen.id = mahasiswa.dosen_wali_id', 'left')
            ->findAll();

        return view('admin/mahasiswa/index', $data);
    }

    public function create()
    {
        return view('admin/mahasiswa/form', [
            'dosen' => $this->dosen->findAll(),
            'mahasiswa' => null
        ]);
    }

    public function store()
    {
        $rules = [
            'nim' => 'required|is_unique[mahasiswa.nim]',
            'nama' => 'required|min_length[3]',
            'program_studi' => 'required',
            'angkatan' => 'required|integer',
            'email' => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $userId = (new UserModel())->insert([
                'username' => $this->request->getPost('nim'),
                'password' => password_hash(
                    $this->request->getPost('nim'),
                    PASSWORD_DEFAULT
                ),
                'role' => 'mahasiswa'
            ], true);

            $this->mahasiswa->insert([
                'user_id' => $userId,
                'dosen_wali_id' => $this->request->getPost('dosen_wali_id') ?: null,
                'nim' => $this->request->getPost('nim'),
                'nama' => $this->request->getPost('nama'),
                'program_studi' => $this->request->getPost('program_studi'),
                'angkatan' => $this->request->getPost('angkatan'),
                'email' => $this->request->getPost('email')
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Gagal menyimpan data.');
            }

            $db->transCommit();

            return redirect()->to('/admin/mahasiswa')
                ->with('success', 'Mahasiswa berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan mahasiswa: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        return view('admin/mahasiswa/form', [
            'mahasiswa' => $this->mahasiswa->find($id),
            'dosen' => $this->dosen->findAll()
        ]);
    }

    public function update($id)
    {
        $data = [
            'dosen_wali_id' => $this->request->getPost('dosen_wali_id') ?: null,
            'nama' => $this->request->getPost('nama'),
            'program_studi' => $this->request->getPost('program_studi'),
            'angkatan' => $this->request->getPost('angkatan'),
            'email' => $this->request->getPost('email')
        ];

        if (!$this->validate([
            'nama' => 'required|min_length[3]',
            'program_studi' => 'required',
            'angkatan' => 'required|integer',
            'email' => 'permit_empty|valid_email'
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->mahasiswa->update($id, $data);

        return redirect()->to('/admin/mahasiswa')
            ->with('success', 'Data mahasiswa diperbarui.');
    }

    public function delete($id)
    {
        $data = $this->mahasiswa->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $this->mahasiswa->delete($id);
            (new UserModel())->delete($data['user_id']);

            $db->transCommit();

            return redirect()->to('/admin/mahasiswa')
                ->with('success', 'Mahasiswa berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}