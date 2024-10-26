<?php

namespace App\Controllers;

use App\Models\UserModel; // Model yang menangani user
use CodeIgniter\Controller;

class Login extends BaseController
{
    public function index()
    {
        // Load view login
        return view('login_form');
    }

    public function authenticate()
    {
        // Ambil input dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Inisialisasi model untuk mengakses data user
        $userModel = new UserModel();

        // Cari user berdasarkan email
        $user = $userModel->where('email', $email)->first();

        // Jika user ditemukan dan password benar
        if ($user && password_verify($password, $user['password'])) {
            // Set session untuk menandakan user sudah login
            session()->set([
                'email' => $user['email'],
                'role' => $user['role'],
                'user_id' => $user['id'], // Menyimpan ID pengguna dalam session
                'isLoggedIn' => true
            ]);

            // Redirect ke halaman dashboard sesuai role dan user
            return redirect()->to("/dashboard/{$user['role']}/{$user['id']}"); // Contoh: /dashboard/marketing/1
        } else {
            // Jika login gagal
            session()->setFlashdata('error', 'Email atau password salah.');
            return redirect()->back();
        }
    }


    public function logout()
    {
        // Hapus session
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        // Load view registrasi
        return view('register_form');
    }

    public function create()
    {
        // Ambil input dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role'); // Ambil role dari input

        // Validasi email dan password (contoh)
        if (!$this->validate([
            'email' => 'required|valid_email|is_unique[users.email]', // Assuming your table is 'users'
            'password' => 'required|min_length[8]', // Aturan untuk password
        ])) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar atau password tidak valid.');
        }

        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Siapkan data untuk disimpan
        $data = [
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role // Menyimpan role pengguna
        ];

        // Inisialisasi model
        $userModel = new UserModel();

        // Simpan data ke database
        if ($userModel->insert($data)) {
            // Berhasil mendaftar
            session()->setFlashdata('success', 'Registration successful. You can now login.');
            return redirect()->to('/login');
        } else {
            // Gagal mendaftar
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
}
