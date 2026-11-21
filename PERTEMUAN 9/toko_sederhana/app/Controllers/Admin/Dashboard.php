<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = db_connect();
        return view('admin/dashboard', [
            'jumlahBarang' => $db->table('barang')->countAllResults(),
            'jumlahPesanan' => $db->table('pesanan')->countAllResults(),
            'pesananBaru' => $db->table('pesanan')->where('status', 'Menunggu')->countAllResults(),
            'totalPenjualan' => $db->table('pesanan')->selectSum('total')->whereNotIn('status', ['Dibatalkan'])->get()->getRow()->total ?? 0,
        ]);
    }
}
