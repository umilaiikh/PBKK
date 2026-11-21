<?php

namespace App\Controllers;

class CheckoutController extends BaseController
{
    public function index()
    {
        $items = db_connect()->table('cart_items ci')->select('ci.*,p.name,p.price,p.stock')
            ->join('carts c','c.id=ci.cart_id')->join('products p','p.id=ci.product_id')
            ->where('c.user_id',session('user_id'))->get()->getResultArray();

        if (!$items) return redirect()->to('/cart')->with('error','Keranjang masih kosong.');
        return $this->page('store/checkout',['title'=>'Checkout','items'=>$items]);
    }

    public function place()
    {
        $rules = [
            'recipient_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'postal_code' => 'required',
        ];
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());

        $db = db_connect();
        $items = $db->table('cart_items ci')->select('ci.*,p.name,p.price,p.stock,p.id AS pid')
            ->join('carts c','c.id=ci.cart_id')->join('products p','p.id=ci.product_id')
            ->where('c.user_id',session('user_id'))->get()->getResultArray();

        if (!$items) return redirect()->to('/cart')->with('error','Keranjang kosong.');

        $db->transStart();
        foreach ($items as $item) {
            if ($item['quantity'] > $item['stock']) {
                $db->transRollback();
                return redirect()->to('/cart')->with('error','Stok '.$item['name'].' tidak mencukupi.');
            }
        }

        $db->table('addresses')->insert([
            'user_id'=>session('user_id'),'recipient_name'=>$this->request->getPost('recipient_name'),
            'phone'=>$this->request->getPost('phone'),'address'=>$this->request->getPost('address'),
            'city'=>$this->request->getPost('city'),'province'=>$this->request->getPost('province'),
            'postal_code'=>$this->request->getPost('postal_code'),'is_default'=>0
        ]);
        $addressId = $db->insertID();
        $subtotal = 0;
        foreach ($items as $item) $subtotal += $item['price'] * $item['quantity'];

        $orderNumber = 'SE-' . date('YmdHis') . '-' . session('user_id');
        $db->table('orders')->insert([
            'user_id'=>session('user_id'),'address_id'=>$addressId,'order_number'=>$orderNumber,
            'order_date'=>date('Y-m-d H:i:s'),'subtotal'=>$subtotal,'shipping_cost'=>0,
            'total'=>$subtotal,'order_status'=>'pending_payment'
        ]);
        $orderId = $db->insertID();

        foreach ($items as $item) {
            $db->table('order_items')->insert([
                'order_id'=>$orderId,'product_id'=>$item['pid'],'product_name'=>$item['name'],
                'price'=>$item['price'],'quantity'=>$item['quantity'],'subtotal'=>$item['price']*$item['quantity']
            ]);
            $db->table('products')->where('id',$item['pid'])->set('stock','stock-'.$item['quantity'],false)->update();
        }

        $db->table('payments')->insert(['order_id'=>$orderId,'method'=>'Transfer Bank','amount'=>$subtotal,'payment_status'=>'unpaid']);
        $db->table('shipments')->insert(['order_id'=>$orderId,'shipment_status'=>'waiting']);
        $db->table('cart_items')->whereIn('cart_id', function($builder) {
            $builder->select('id')->from('carts')->where('user_id',session('user_id'));
        })->delete();

        $db->transComplete();
        if ($db->transStatus() === false) return redirect()->to('/cart')->with('error','Checkout gagal. Silakan coba lagi.');
        return redirect()->to('/orders')->with('success','Pesanan berhasil dibuat. Silakan upload bukti pembayaran.');
    }
}
