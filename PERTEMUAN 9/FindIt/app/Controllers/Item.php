<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Item extends BaseController
{
    private function rules(): array
    {
        return [
            'item_name' => 'required|max_length[100]',
            'category'  => 'required|max_length[50]',
            'location'  => 'required|max_length[100]',
            'date'      => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[Lost,Found]',
        ];
    }

    private function uploadImage(): ?string
    {
        $file = $this->request->getFile('image');

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException('Upload gambar gagal.');
        }

        $validation = service('validation');
        $validation->setRules([
            'image' => [
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
                'errors' => [
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                    'max_size' => 'Ukuran gambar maksimal 2 MB.'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            throw new \RuntimeException(
                implode(' ', $validation->getErrors())
            );
        }

        $folder = FCPATH . 'uploads/items/';

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($folder, $newName);

        return $newName;
    }

    private function removeImage(?string $filename): void
    {
        if (!$filename) {
            return;
        }

        $path = FCPATH . 'uploads/items/' . basename($filename);

        if (is_file($path)) {
            unlink($path);
        }
    }

    public function index()
    {
        $model = new ItemModel();

        return view('items/index', [
            'items' => $model->orderBy('id', 'DESC')->findAll()
        ]);
    }

    public function create()
    {
        return view('items/create');
    }

    public function store()
    {
        if (!$this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $image = $this->uploadImage();
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $model = new ItemModel();

        $model->insert([
            'item_name' => $this->request->getPost('item_name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'image' => $image,
            'location' => $this->request->getPost('location'),
            'date' => $this->request->getPost('date'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/items')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('items/edit', ['item' => $item]);
    }

    public function update($id)
    {
        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!$this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        try {
            $newImage = $this->uploadImage();
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $data = [
            'item_name' => $this->request->getPost('item_name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'date' => $this->request->getPost('date'),
            'status' => $this->request->getPost('status')
        ];

        if ($newImage) {
            $data['image'] = $newImage;
        }

        $model->update($id, $data);

        if ($newImage) {
            $this->removeImage($item['image'] ?? null);
        }

        return redirect()->to('/items')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Hanya Admin yang boleh menghapus.
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/items')
                ->with('error', 'Kamu tidak memiliki izin untuk menghapus laporan.');
        }

        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model->delete($id);
        $this->removeImage($item['image'] ?? null);

        return redirect()->to('/items')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}