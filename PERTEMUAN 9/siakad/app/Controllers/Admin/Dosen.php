<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\UserModel;

class Dosen extends BaseController
{
    public function index()
    {
        return view('admin/dosen/index', [
            'dosen' => (new DosenModel())->findAll()
        ]);
    }

    public function store()
    {
        $rules = [
            'nip' => 'required|is_unique[dosen.nip]',
            'nama' => 'required|min_length[3]',
            'email' => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $nip = $this->request->getPost('nip');

            $userId = (new UserModel())->insert([
                'username' => $nip,
                'password' => password_hash($nip, PASSWORD_DEFAULT),
                'role' => 'dosen'
            ], true);

            (new DosenModel())->insert([
                'user_id' => $userId,
                'nip' => $nip,
                'nama' => $this->request->getPost('nama'),
                'email' => $this->request->getPost('email'),
                'no_telepon' => $this->request->getPost('no_telepon')
            ]);

            $db->transCommit();

            return redirect()->to('/admin/dosen')
                ->with('success', 'Dosen berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()->with('error', 'Gagal menambah dosen.');
        }
    }

    public function update($id)
    {
        (new DosenModel())->update($id, [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('no_telepon')
        ]);

        return redirect()->to('/admin/dosen')
            ->with('success', 'Data dosen diperbarui.');
    }

    public function delete($id)
    {
        $model = new DosenModel();
        $dosen = $model->find($id);

        if (!$dosen) {
            return redirect()->back()->with('error', 'Dosen tidak ditemukan.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $model->delete($id);
            (new UserModel())->delete($dosen['user_id']);

            $db->transCommit();

            return redirect()->to('/admin/dosen')
                ->with('success', 'Dosen berhasil dihapus.');
        } catch (\Throwable $e) {
            $db->transRollback();

            return redirect()->back()->with('error', 'Gagal menghapus dosen.');
        }
    }
}