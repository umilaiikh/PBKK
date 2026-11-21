<?php

namespace App\Controllers;

class OrderController extends BaseController
{
    public function index()
    {
        $orders = db_connect()->table('orders')->where('user_id',session('user_id'))->orderBy('id','DESC')->get()->getResultArray();
        return $this->page('store/orders',['title'=>'Pesanan Saya','orders'=>$orders]);
    }

    public function detail($id)
    {
        $db = db_connect();
        $order = $db->table('orders')->where('id',$id)->where('user_id',session('user_id'))->get()->getRowArray();
        if (!$order) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $items = $db->table('order_items')->where('order_id',$id)->get()->getResultArray();
        $payment = $db->table('payments')->where('order_id',$id)->get()->getRowArray();
        $shipment = $db->table('shipments')->where('order_id',$id)->get()->getRowArray();
        return $this->page('store/order_detail',['title'=>'Detail Pesanan','order'=>$order,'items'=>$items,'payment'=>$payment,'shipment'=>$shipment]);
    }

    public function uploadPayment($id)
    {
        $db = db_connect();
        $order = $db->table('orders')->where('id',$id)->where('user_id',session('user_id'))->get()->getRowArray();
        if (!$order || $order['order_status'] !== 'pending_payment') return redirect()->back()->with('error','Pesanan tidak dapat menerima pembayaran.');

        $file = $this->request->getFile('proof');
        if (!$file || !$file->isValid() || !in_array($file->getMimeType(),['image/jpeg','image/png','application/pdf'],true) || $file->getSizeByUnit('mb') > 2) {
            return redirect()->back()->with('error','Pilih JPG, PNG, atau PDF maksimal 2 MB.');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH.'uploads/payments',$newName);
        $db->table('payments')->where('order_id',$id)->update(['proof_file'=>$newName,'payment_status'=>'submitted']);
        $db->table('orders')->where('id',$id)->update(['order_status'=>'waiting_verification']);
        return redirect()->back()->with('success','Bukti pembayaran berhasil dikirim.');
    }
}
