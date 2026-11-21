<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Barang extends BaseController
{
    public function index()
    {
        $barang = db_connect()->table('barang')->orderBy('id', 'DESC')->get()->getResultArray();
        return view('admin/barang_index', ['barang' => $barang]);
    }

    public function tambah()
    {
        return view('admin/barang_form', ['barang' => null, 'aksi' => 'simpan']);
    }

    public function simpan()
    {
        $rules = [
            'nama' => 'required|min_length[2]|max_length[150]',
            'harga' => 'required|decimal|greater_than_equal_to[0]',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'gambar' => 'permit_empty|valid_url_strict',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        db_connect()->table('barang')->insert([
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'gambar' => $this->request->getPost('gambar'),
        ]);
        return redirect()->to('/admin/barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = db_connect()->table('barang')->where('id', $id)->get()->getRowArray();
        if (! $barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }
        return view('admin/barang_form', ['barang' => $barang, 'aksi' => 'update/' . $id]);
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required|min_length[2]|max_length[150]',
            'harga' => 'required|decimal|greater_than_equal_to[0]',
            'stok' => 'required|integer|greater_than_equal_to[0]',
            'gambar' => 'permit_empty|valid_url_strict',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        db_connect()->table('barang')->where('id', $id)->update([
            'nama' => $this->request->getPost('nama'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'gambar' => $this->request->getPost('gambar'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/barang')->with('success', 'Barang berhasil diperbarui.');
    }

    public function hapus($id)
    {
        db_connect()->table('barang')->where('id', $id)->delete();
        return redirect()->to('/admin/barang')->with('success', 'Barang berhasil dihapus.');
    }
}
