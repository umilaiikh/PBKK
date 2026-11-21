<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Pesanan extends BaseController
{
    public function index()
    {
        $pesanan = db_connect()->table('pesanan')->orderBy('id', 'DESC')->get()->getResultArray();
        return view('admin/pesanan_index', ['pesanan' => $pesanan]);
    }

    public function status($id)
    {
        $status = $this->request->getPost('status');
        $allowed = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
        if (! in_array($status, $allowed, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $db = db_connect();
        $order = $db->table('pesanan')->where('id', $id)->get()->getRowArray();
        if (! $order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $db->transBegin();
        if ($status === 'Dibatalkan' && $order['status'] !== 'Dibatalkan') {
            $items = $db->table('detail_pesanan')->where('pesanan_id', $id)->get()->getResultArray();
            foreach ($items as $item) {
                if ($item['barang_id']) {
                    $db->table('barang')->set('stok', 'stok + ' . (int) $item['jumlah'], false)->where('id', $item['barang_id'])->update();
                }
            }
        }
        $db->table('pesanan')->where('id', $id)->update(['status' => $status]);
        $db->transCommit();

        return redirect()->to('/admin/pesanan')->with('success', 'Status pesanan diperbarui.');
    }
}
