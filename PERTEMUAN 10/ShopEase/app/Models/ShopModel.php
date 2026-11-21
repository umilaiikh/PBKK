<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['category_id','name','slug','description','price','stock','image','status'];
    protected $useTimestamps = true;

    public function products($keyword = '', $category = '')
    {
        $builder = $this->db->table('products p')
            ->select('p.*, c.name AS category_name')
            ->join('categories c', 'c.id=p.category_id')
            ->where('p.status', 'active');

        if ($keyword !== '') $builder->like('p.name', $keyword);
        if ($category !== '') $builder->where('p.category_id', $category);

        return $builder->orderBy('p.id', 'DESC')->get()->getResultArray();
    }

    public function categories()
    {
        return $this->db->table('categories')->where('is_active', 1)->orderBy('name')->get()->getResultArray();
    }
}
