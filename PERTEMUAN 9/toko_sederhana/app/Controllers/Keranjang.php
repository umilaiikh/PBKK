<?php

namespace App\Controllers;

class Keranjang extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        $items = [];
        $total = 0;

        if ($cart) {
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
        }

        return view('toko/keranjang', ['items' => $items, 'total' => $total]);
    }

    public function tambah($id)
    {
        $barang = db_connect()->table('barang')->where('id', $id)->get()->getRowArray();
        if (! $barang || $barang['stok'] < 1) {
            return redirect()->back()->with('error', 'Barang tidak tersedia atau stok habis.');
        }

        $cart = session()->get('cart') ?? [];
        $cart[$id] = min(($cart[$id] ?? 0) + 1, (int) $barang['stok']);
        session()->set('cart', $cart);

        return redirect()->to('/keranjang')->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function update()
    {
        $cart = session()->get('cart') ?? [];
        $quantities = $this->request->getPost('jumlah') ?? [];
        foreach ($quantities as $id => $qty) {
            $qty = max(0, (int) $qty);
            $barang = db_connect()->table('barang')->where('id', (int) $id)->get()->getRowArray();
            if (! $barang || $qty === 0) {
                unset($cart[$id]);
            } else {
                $cart[$id] = min($qty, (int) $barang['stok']);
            }
        }
        session()->set('cart', $cart);
        return redirect()->to('/keranjang')->with('success', 'Keranjang diperbarui.');
    }

    public function hapus($id)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$id]);
        session()->set('cart', $cart);
        return redirect()->to('/keranjang')->with('success', 'Barang dihapus dari keranjang.');
    }
}
