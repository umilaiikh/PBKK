<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function register()
    {
        return $this->page('auth/register', ['title' => 'Daftar']);
    }

    public function saveRegister()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        db_connect()->table('users')->insert([
            'name' => $this->request->getPost('name'),
            'email' => strtolower($this->request->getPost('email')),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'customer',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function login()
    {
        return $this->page('auth/login', ['title' => 'Login']);
    }

    public function attemptLogin()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $user = db_connect()->table('users')->where('email', $email)->where('is_active', 1)->get()->getRowArray();

        if (! $user || ! password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password tidak sesuai.');
        }

        session()->regenerate();
        session()->set([
            'user_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true,
        ]);

        if ($user['role'] === 'admin') return redirect()->to('/admin');
        if ($user['role'] === 'operator') return redirect()->to('/operator/orders');
        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Kamu berhasil logout.');
    }
}
