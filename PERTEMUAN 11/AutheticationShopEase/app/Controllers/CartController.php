<?php

namespace App\Controllers;

class CartController extends BaseController
{
    private function cartId(): int
    {
        $db = db_connect();
        $cart = $db->table('carts')->where('user_id', session('user_id'))->get()->getRowArray();
        if ($cart) return (int) $cart['id'];

        $db->table('carts')->insert(['user_id' => session('user_id'), 'created_at' => date('Y-m-d H:i:s')]);
        return (int) $db->insertID();
    }

    public function index()
    {
        $items = db_connect()->table('cart_items ci')
            ->select('ci.id, ci.quantity, p.name, p.price, p.stock, p.image, (ci.quantity*p.price) AS subtotal')
            ->join('carts c', 'c.id=ci.cart_id')
            ->join('products p', 'p.id=ci.product_id')
            ->where('c.user_id', session('user_id'))->get()->getResultArray();

        return $this->page('store/cart', ['title' => 'Keranjang', 'items' => $items]);
    }

    public function add($productId)
    {
        $db = db_connect();
        $product = $db->table('products')->where('id', $productId)->where('status', 'active')->get()->getRowArray();
        if (! $product || $product['stock'] < 1) return redirect()->back()->with('error', 'Produk tidak tersedia.');

        $cartId = $this->cartId();
        $existing = $db->table('cart_items')->where('cart_id', $cartId)->where('product_id', $productId)->get()->getRowArray();
        $qty = (int) ($existing['quantity'] ?? 0) + max(1, (int) $this->request->getPost('quantity'));
        if ($qty > $product['stock']) return redirect()->back()->with('error', 'Jumlah melebihi stok.');

        if ($existing) $db->table('cart_items')->where('id', $existing['id'])->update(['quantity' => $qty]);
        else $db->table('cart_items')->insert(['cart_id' => $cartId, 'product_id' => $productId, 'quantity' => $qty]);

        return redirect()->to('/cart')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update($id)
    {
        $qty = (int) $this->request->getPost('quantity');
        $item = db_connect()->table('cart_items ci')->select('ci.*, p.stock')->join('carts c','c.id=ci.cart_id')->join('products p','p.id=ci.product_id')->where('ci.id',$id)->where('c.user_id',session('user_id'))->get()->getRowArray();
        if ($item && $qty >= 1 && $qty <= $item['stock']) db_connect()->table('cart_items')->where('id',$id)->update(['quantity'=>$qty]);
        return redirect()->to('/cart');
    }

    public function remove($id)
    {
        db_connect()->table('cart_items ci')->where('ci.id',$id)->whereIn('ci.cart_id', function($builder) {
            $builder->select('id')->from('carts')->where('user_id', session('user_id'));
        })->delete();
        return redirect()->to('/cart')->with('success', 'Item dihapus.');
    }
}
