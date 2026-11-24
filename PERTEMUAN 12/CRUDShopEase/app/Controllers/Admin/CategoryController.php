<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CategoryController extends BaseController
{
    protected $categories;
    protected $products;

    public function __construct()
    {
        $this->categories = new CategoryModel();
        $this->products = new ProductModel();
    }

    public function index()
    {
        return $this->page('admin/categories/index', [
            'title' => 'Kelola Kategori',
            'categories' => $this->categories->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return $this->page('admin/categories/form', ['title' => 'Tambah Kategori', 'category' => null]);
    }

    public function store()
    {
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
        ];
        if (!$this->validate(['name' => 'required|min_length[3]|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $id = $this->categories->insert([
            'name' => $data['name'],
            'slug' => url_title($data['name'], '-', true) . '-' . time(),
            'description' => $data['description'],
            'is_active' => 1,
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = $this->categories->find($id);
        if (!$category) throw PageNotFoundException::forPageNotFound();
        return $this->page('admin/categories/form', ['title' => 'Edit Kategori', 'category' => $category]);
    }

    public function update($id)
    {
        $category = $this->categories->find($id);
        if (!$category) throw PageNotFoundException::forPageNotFound();
        if (!$this->validate(['name' => 'required|min_length[3]|max_length[100]'])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $name = trim((string) $this->request->getPost('name'));
        $this->categories->update($id, [
            'name' => $name,
            'slug' => url_title($name, '-', true) . '-' . $id,
            'description' => trim((string) $this->request->getPost('description')),
        ]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (!$this->categories->find($id)) return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        if ($this->products->where('category_id', $id)->countAllResults() > 0) {
            return redirect()->back()->with('error', 'Kategori masih digunakan oleh produk. Pindahkan produk terlebih dahulu.');
        }
        $this->categories->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
