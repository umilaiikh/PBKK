<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/admin');
        }
        return view('admin/login');
    }

    public function attempt()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = db_connect()->table('users')->where('username', $username)->where('role', 'admin')->get()->getRowArray();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->regenerate();
        session()->set([
            'admin_logged_in' => true,
            'admin_id' => $user['id'],
            'admin_nama' => $user['nama'],
        ]);
        return redirect()->to('/admin');
    }

    public function logout()
    {
        session()->remove(['admin_logged_in', 'admin_id', 'admin_nama']);
        session()->regenerate();
        return redirect()->to('/admin/login')->with('success', 'Berhasil logout.');
    }
}
