<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Item extends BaseController
{
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
        $rules = [
            'item_name' => 'required|max_length[100]',
            'category' => 'required',
            'location' => 'required|max_length[100]',
            'date' => 'required|valid_date[Y-m-d]',
            'status' => 'required|in_list[Lost,Found]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new ItemModel();

        $model->insert([
            'item_name' => $this->request->getPost('item_name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'date' => $this->request->getPost('date'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/items')
            ->with('success', 'Item successfully added!');
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

        $rules = [
            'item_name' => 'required|max_length[100]',
            'category' => 'required',
            'location' => 'required|max_length[100]',
            'date' => 'required|valid_date[Y-m-d]',
            'status' => 'required|in_list[Lost,Found]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'item_name' => $this->request->getPost('item_name'),
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
            'location' => $this->request->getPost('location'),
            'date' => $this->request->getPost('date'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/items')
            ->with('success', 'Item successfully updated!');
    }

    public function delete($id)
    {
        $model = new ItemModel();
        $model->delete($id);

        return redirect()->to('/items')
            ->with('success', 'Item successfully deleted!');
    }
}