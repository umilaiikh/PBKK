<?php

namespace App\Controllers;

class Checkout extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        if (! $cart) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang masih kosong.');
        }

        $items = [];
        $total = 0;
        $rows = db_connect()->table('barang')->whereIn('id', array_keys($cart))->get()->getResultArray();
        foreach ($rows as $row) {
            $qty = min((int) $cart[$row['id']], (int) $row['stok']);
            if ($qty > 0) {
                $row['jumlah'] = $qty;
                $row['subtotal'] = $qty * (float) $row['harga'];
                $items[] = $row;
                $total += $row['subtotal'];
            }
        }
        return view('toko/checkout', ['items' => $items, 'total' => $total]);
    }

    public function proses()
    {
        $rules = [
            'nama_pembeli' => 'required|min_length[3]|max_length[100]',
            'telepon' => 'required|min_length[8]|max_length[30]',
            'alamat' => 'required|min_length[8]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cart = session()->get('cart') ?? [];
        if (! $cart) {
            return redirect()->to('/keranjang')->with('error', 'Keranjang masih kosong.');
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $rows = $db->table('barang')->whereIn('id', array_keys($cart))->get()->getResultArray();
            if (count($rows) !== count($cart)) {
                throw new \RuntimeException('Ada barang yang sudah tidak tersedia.');
            }

            $total = 0;
            $details = [];
            foreach ($rows as $row) {
                $qty = (int) $cart[$row['id']];
                if ($qty < 1 || (int) $row['stok'] < $qty) {
                    throw new \RuntimeException('Stok ' . $row['nama'] . ' tidak mencukupi.');
                }
                $subtotal = (float) $row['harga'] * $qty;
                $total += $subtotal;
                $details[] = [
                    'barang_id' => $row['id'],
                    'nama_barang' => $row['nama'],
                    'harga' => $row['harga'],
                    'jumlah' => $qty,
                    'subtotal' => $subtotal,
                ];
            }

            $kode = 'PSN' . date('YmdHis') . random_int(10, 99);
            $db->table('pesanan')->insert([
                'kode_pesanan' => $kode,
                'nama_pembeli' => $this->request->getPost('nama_pembeli'),
                'telepon' => $this->request->getPost('telepon'),
                'alamat' => $this->request->getPost('alamat'),
                'total' => $total,
                'status' => 'Menunggu',
            ]);
            $pesananId = $db->insertID();

            foreach ($details as $detail) {
                $db->table('detail_pesanan')->insert(array_merge(['pesanan_id' => $pesananId], $detail));
                $db->table('barang')
                    ->set('stok', 'stok - ' . (int) $detail['jumlah'], false)
                    ->where('id', $detail['barang_id'])
                    ->where('stok >=', (int) $detail['jumlah'])
                    ->update();

                if ($db->affectedRows() !== 1) {
                    throw new \RuntimeException('Stok barang berubah. Silakan ulangi checkout.');
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Pesanan gagal disimpan.');
            }
            $db->transCommit();
            session()->remove('cart');
            return redirect()->to('/pesanan/' . $kode)->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to('/keranjang')->with('error', $e->getMessage());
        }
    }

    public function detail($kode)
    {
        $pesanan = db_connect()->table('pesanan')->where('kode_pesanan', $kode)->get()->getRowArray();
        if (! $pesanan) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }
        $detail = db_connect()->table('detail_pesanan')->where('pesanan_id', $pesanan['id'])->get()->getResultArray();
        return view('toko/pesanan', ['pesanan' => $pesanan, 'detail' => $detail]);
    }
}
