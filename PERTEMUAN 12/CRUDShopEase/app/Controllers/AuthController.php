<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function register()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole();
        }

        return $this->page('auth/register', [
            'title' => 'Daftar'
        ]);
    }

    public function saveRegister()
    {
        $data = [
            'name' => trim((string) $this->request->getPost('name')),
            'email' => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => (string) $this->request->getPost('password'),
        ];

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        db_connect()->table('users')->insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            'role' => 'customer',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole();
        }

        return $this->page('auth/login', [
            'title' => 'Login'
        ]);
    }

    public function attemptLogin()
    {
        $email = strtolower(trim(
            (string) $this->request->getPost('email')
        ));

        $password = (string) $this->request->getPost('password');

        // Membuat cache key yang aman dari karakter khusus.
        $ipKey = 'login-' . hash(
            'sha256',
            $this->request->getIPAddress()
        );

        // Membatasi percobaan login maksimal 5 kali dalam 60 detik.
        $throttler = service('throttler');

        if (! $throttler->check($ipKey, 5, 60)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terlalu banyak percobaan login. Silakan coba lagi nanti.');
        }

        // Mencari akun berdasarkan email dan status aktif.
        $user = db_connect()->table('users')
            ->where('email', $email)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        // Memeriksa password dengan password hash.
        if (
            ! $user ||
            ! password_verify($password, $user['password_hash'])
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password tidak sesuai.');
        }

        // Memperbarui session ID setelah login berhasil.
        session()->regenerate(true);

        // Menyimpan informasi pengguna ke session.
        session()->set([
            'user_id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true,
        ]);

        // Mengarahkan pengguna berdasarkan role.
        return $this->redirectByRole();
    }

    public function logout()
    {
        // Menghapus informasi autentikasi dari session.
        session()->remove([
            'user_id',
            'name',
            'email',
            'role',
            'logged_in',
        ]);

        // Memperbarui session ID.
        session()->regenerate(true);

        return redirect()->to('/')
            ->with('success', 'Kamu berhasil logout.');
    }

    private function redirectByRole()
    {
        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        if ($role === 'operator') {
            return redirect()->to('/operator/orders');
        }

        return redirect()->to('/');
    }
}