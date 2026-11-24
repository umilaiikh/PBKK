<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use App\Models\ProductImageModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class ProductController extends BaseController
{
    protected $products;
    protected $categories;
    protected $images;

    public function __construct()
    {
        $this->products = new ProductModel();
        $this->categories = new CategoryModel();
        $this->images = new ProductImageModel();
    }

    public function index()
    {
        $builder = $this->products
            ->select('products.*, categories.name AS category_name')
            ->join('categories', 'categories.id = products.category_id');
        $keyword = trim((string) $this->request->getGet('q'));
        $categoryId = $this->request->getGet('category');
        if ($keyword !== '') $builder->groupStart()->like('products.name', $keyword)->orLike('products.description', $keyword)->groupEnd();
        if ($categoryId !== null && ctype_digit((string) $categoryId) && (int) $categoryId > 0) $builder->where('products.category_id', (int) $categoryId);
        $products = $builder->orderBy('products.id', 'DESC')->findAll();
        return $this->page('admin/products/index', [
            'title' => 'Kelola Produk',
            'products' => $products,
            'categories' => $this->categories->where('is_active', 1)->findAll(),
            'keyword' => $keyword,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function create()
    {
        return $this->page('admin/products/form', [
            'title' => 'Tambah Produk', 'product' => null,
            'categories' => $this->categories->where('is_active', 1)->findAll(), 'images' => [],
        ]);
    }

    public function store()
    {
        if (!$this->validate($this->rules())) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $name = trim((string) $this->request->getPost('name'));
        $id = $this->products->insert([
            'category_id' => (int) $this->request->getPost('category_id'),
            'name' => $name,
            'slug' => url_title($name, '-', true) . '-' . time(),
            'description' => trim((string) $this->request->getPost('description')),
            'price' => (float) $this->request->getPost('price'),
            'stock' => (int) $this->request->getPost('stock'),
            'status' => $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active',
        ]);
        $this->saveImages((int) $id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = $this->products->find($id);
        if (!$product) throw PageNotFoundException::forPageNotFound();
        return $this->page('admin/products/form', [
            'title' => 'Edit Produk', 'product' => $product,
            'categories' => $this->categories->where('is_active', 1)->findAll(),
            'images' => $this->images->where('product_id', $id)->findAll(),
        ]);
    }

    public function update($id)
    {
        $product = $this->products->find($id);
        if (!$product) throw PageNotFoundException::forPageNotFound();
        if (!$this->validate($this->rules())) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $name = trim((string) $this->request->getPost('name'));
        $this->products->update($id, [
            'category_id' => (int) $this->request->getPost('category_id'),
            'name' => $name,
            'slug' => url_title($name, '-', true) . '-' . $id,
            'description' => trim((string) $this->request->getPost('description')),
            'price' => (float) $this->request->getPost('price'),
            'stock' => (int) $this->request->getPost('stock'),
            'status' => $this->request->getPost('status') === 'inactive' ? 'inactive' : 'active',
        ]);
        $this->saveImages((int) $id);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $product = $this->products->find($id);
        if (!$product) return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        // Nonaktifkan produk agar riwayat transaksi lama tetap aman.
        $this->products->update($id, ['status' => 'inactive']);
        return redirect()->to('/admin/products')->with('success', 'Produk berhasil dinonaktifkan.');
    }

    public function deleteImage($imageId)
    {
        $image = $this->images->find($imageId);
        if (!$image) return redirect()->back()->with('error', 'Gambar tidak ditemukan.');
        $path = FCPATH . 'uploads/products/' . $image['image'];
        if (is_file($path)) @unlink($path);
        $this->images->delete($imageId);
        $product = $this->products->find($image['product_id']);
        if ($product && $product['image'] === $image['image']) {
            $next = $this->images->where('product_id', $image['product_id'])->first();
            $this->products->update($image['product_id'], ['image' => $next['image'] ?? null]);
        }
        return redirect()->back()->with('success', 'Gambar berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'category_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[3]|max_length[150]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'stock' => 'required|is_natural',
            'status' => 'required|in_list[active,inactive]',
        ];
    }

    private function saveImages(int $productId): void
    {
        $files = $this->request->getFileMultiple('images');
        if (!$files) return;
        $directory = FCPATH . 'uploads/products/';
        if (!is_dir($directory)) mkdir($directory, 0755, true);
        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) continue;
            if ($file->getSizeByUnit('mb') > 2) continue;
            $filename = $file->getRandomName();
            $file->move($directory, $filename);
            $isPrimary = $this->images->where('product_id', $productId)->countAllResults() === 0;
            $this->images->insert(['product_id' => $productId, 'image' => $filename, 'is_primary' => $isPrimary ? 1 : 0]);
            if ($isPrimary) $this->products->update($productId, ['image' => $filename]);
        }
    }
}
