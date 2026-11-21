<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        $user = $userModel
            ->where('username', $username)
            ->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Username atau password salah.');
        }

        session()->regenerate();

        session()->set([
            'user_id'  => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        if ($role === 'dosen') {
            return redirect()->to('/dosen');
        }

        return redirect()->to('/mahasiswa');
    }
}