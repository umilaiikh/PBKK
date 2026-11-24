<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $db = db_connect();
        $data = [
            'title'=>'Dashboard',
            'productCount'=>$db->table('products')->countAllResults(),
            'orderCount'=>$db->table('orders')->countAllResults(),
            'customerCount'=>$db->table('users')->where('role','customer')->countAllResults(),
            'sales'=>$db->table('orders')->selectSum('total')->whereIn('order_status',['processing','shipped','completed'])->get()->getRow()->total ?? 0,
        ];
        return $this->page('admin/dashboard',$data);
    }

    public function categories()
    {
        return $this->page('admin/categories',['title'=>'Kategori','categories'=>db_connect()->table('categories')->orderBy('id','DESC')->get()->getResultArray()]);
    }

    public function saveCategory()
    {
        $id=(int)$this->request->getPost('id');
        $name=trim((string)$this->request->getPost('name'));
        if ($name==='') return redirect()->back()->with('error','Nama kategori wajib diisi.');
        $data=['name'=>$name,'slug'=>url_title($name,'-',true),'description'=>$this->request->getPost('description'),'is_active'=>1];
        $db=db_connect();
        if($id) $db->table('categories')->where('id',$id)->update($data);
        else $db->table('categories')->insert($data);
        return redirect()->to('/admin/categories')->with('success','Kategori disimpan.');
    }

    public function deleteCategory($id)
    {
        $used=db_connect()->table('products')->where('category_id',$id)->countAllResults();
        if($used) return redirect()->back()->with('error','Kategori masih digunakan produk.');
        db_connect()->table('categories')->where('id',$id)->delete();
        return redirect()->back()->with('success','Kategori dihapus.');
    }

    public function products()
    {
        $products=db_connect()->table('products p')->select('p.*,c.name AS category_name')->join('categories c','c.id=p.category_id')->orderBy('p.id','DESC')->get()->getResultArray();
        return $this->page('admin/products',['title'=>'Produk','products'=>$products]);
    }

    public function productForm($id=null)
    {
        $product=$id ? db_connect()->table('products')->where('id',$id)->get()->getRowArray() : null;
        return $this->page('admin/product_form',['title'=>$id?'Edit Produk':'Tambah Produk','product'=>$product,'categories'=>db_connect()->table('categories')->where('is_active',1)->get()->getResultArray()]);
    }

    public function saveProduct()
    {
        $id=(int)$this->request->getPost('id');
        $name=trim((string)$this->request->getPost('name'));
        $price=(float)$this->request->getPost('price');
        $stock=(int)$this->request->getPost('stock');
        $category=(int)$this->request->getPost('category_id');
        if($name==='' || $price<0 || $stock<0 || !$category) return redirect()->back()->withInput()->with('error','Periksa kembali data produk.');

        $data=['category_id'=>$category,'name'=>$name,'slug'=>url_title($name,'-',true).'-'.time(),'description'=>$this->request->getPost('description'),'price'=>$price,'stock'=>$stock,'status'=>$this->request->getPost('status')==='inactive'?'inactive':'active'];
        $image=$this->request->getFile('image');
        if($image && $image->isValid() && in_array($image->getMimeType(),['image/jpeg','image/png','image/webp'],true) && $image->getSizeByUnit('mb')<=2) {
            $newName=$image->getRandomName();
            $image->move(FCPATH.'uploads/products',$newName);
            $data['image']=$newName;
        }
        $db=db_connect();
        if($id) $db->table('products')->where('id',$id)->update($data);
        else $db->table('products')->insert($data);
        return redirect()->to('/admin/products')->with('success','Produk disimpan.');
    }

    public function deleteProduct($id)
    {
        db_connect()->table('products')->where('id',$id)->update(['status'=>'inactive']);
        return redirect()->back()->with('success','Produk dinonaktifkan.');
    }

    public function orders()
    {
        $orders=db_connect()->table('orders o')->select('o.*,u.name AS customer,p.payment_status,s.courier,s.tracking_number')->join('users u','u.id=o.user_id')->join('payments p','p.order_id=o.id','left')->join('shipments s','s.order_id=o.id','left')->orderBy('o.id','DESC')->get()->getResultArray();
        return $this->page('admin/orders',['title'=>'Kelola Pesanan','orders'=>$orders]);
    }

    public function verifyPayment($id,$decision)
    {
        if(!in_array($decision,['verified','rejected'],true)) return redirect()->back();
        $db=db_connect();
        $db->table('payments')->where('order_id',$id)->update(['payment_status'=>$decision,'verified_by'=>session('user_id'),'verified_at'=>date('Y-m-d H:i:s')]);
        $db->table('orders')->where('id',$id)->update(['order_status'=>$decision==='verified'?'processing':'pending_payment']);
        return redirect()->back()->with('success','Status pembayaran diperbarui.');
    }

    public function updateStatus($id)
    {
        $status=(string)$this->request->getPost('order_status');
        $allowed=['processing','shipped','completed','cancelled'];
        if(!in_array($status,$allowed,true)) return redirect()->back()->with('error','Status tidak valid.');
        $db=db_connect();
        $db->table('orders')->where('id',$id)->update(['order_status'=>$status]);
        $shipment=['shipment_status'=>$status];
        if($status==='shipped') {
            $shipment['courier']=$this->request->getPost('courier');
            $shipment['tracking_number']=$this->request->getPost('tracking_number');
            $shipment['shipped_at']=date('Y-m-d H:i:s');
        }
        if($status==='completed') $shipment['delivered_at']=date('Y-m-d H:i:s');
        $db->table('shipments')->where('order_id',$id)->update($shipment);
        return redirect()->back()->with('success','Status pesanan diperbarui.');
    }
}
