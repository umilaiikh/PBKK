<?php

namespace App\Controllers;

class Toko extends BaseController
{
    public function index()
    {
        $barang = db_connect()->table('barang')->orderBy('id', 'DESC')->get()->getResultArray();
        return view('toko/index', ['barang' => $barang]);
    }

    public function detail($id)
    {
        $barang = db_connect()->table('barang')->where('id', $id)->get()->getRowArray();
        if (! $barang) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Barang tidak ditemukan.');
        }
        return view('toko/detail', ['barang' => $barang]);
    }
}
