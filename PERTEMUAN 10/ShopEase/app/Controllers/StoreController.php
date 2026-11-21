<?php

namespace App\Controllers;

use App\Models\ShopModel;

class StoreController extends BaseController
{
    public function index()
    {
        $model = new ShopModel();
        $keyword = trim((string) $this->request->getGet('q'));
        $category = trim((string) $this->request->getGet('category'));

        return $this->page('store/index', [
            'title' => 'Belanja',
            'products' => $model->products($keyword, $category),
            'categories' => $model->categories(),
            'keyword' => $keyword,
            'selectedCategory' => $category,
        ]);
    }

    public function detail($id)
    {
        $product = db_connect()->table('products p')
            ->select('p.*, c.name AS category_name')
            ->join('categories c', 'c.id=p.category_id')
            ->where('p.id', $id)->where('p.status', 'active')->get()->getRowArray();

        if (! $product) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        return $this->page('store/detail', ['title' => $product['name'], 'product' => $product]);
    }
}
