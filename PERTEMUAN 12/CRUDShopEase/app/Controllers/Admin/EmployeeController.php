<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EmployeeController extends BaseController
{
    protected $employees;

    public function __construct()
    {
        $this->employees = new EmployeeModel();
    }

    public function index()
    {
        return $this->page('admin/employees/index', [
            'title' => 'Data Karyawan',
            'employees' => $this->employees->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return $this->page('admin/employees/form', ['title' => 'Tambah Karyawan', 'employee' => null]);
    }

    public function store()
    {
        if (!$this->validate($this->rules())) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $this->employees->insert($this->formData());
        return redirect()->to('/admin/employees')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $employee = $this->employees->find($id);
        if (!$employee) throw PageNotFoundException::forPageNotFound();
        return $this->page('admin/employees/form', ['title' => 'Edit Karyawan', 'employee' => $employee]);
    }

    public function update($id)
    {
        if (!$this->employees->find($id)) throw PageNotFoundException::forPageNotFound();
        if (!$this->validate($this->rules())) return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        $this->employees->update($id, $this->formData());
        return redirect()->to('/admin/employees')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (!$this->employees->find($id)) return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        $this->employees->delete($id);
        return redirect()->to('/admin/employees')->with('success', 'Data karyawan berhasil dihapus.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|min_length[3]|max_length[100]',
            'address' => 'required|min_length[5]',
            'job' => 'required|min_length[2]|max_length[100]',
        ];
    }

    private function formData(): array
    {
        return [
            'name' => trim((string) $this->request->getPost('name')),
            'address' => trim((string) $this->request->getPost('address')),
            'job' => trim((string) $this->request->getPost('job')),
        ];
    }
}
