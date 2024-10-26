<?php

namespace App\Controllers;

use App\Models\PenawaranModel;

class Dashboard extends BaseController
{
    protected $penawaranModel;

    public function __construct()
    {
        $this->penawaranModel = new PenawaranModel();
    }

    public function index()
    {
        // Ambil peran dan user ID dari session
        $role = session()->get('role');
        $userId = session()->get('user_id');

        // Inisialisasi variabel untuk menyimpan penawaran
        $penawaran = [];

        // Ambil penawaran sesuai dengan peran dan user
        if ($role === 'marketing') {
            // Mengambil penawaran berdasarkan user
            $penawaran = $this->penawaranModel->getPenawaranByUser($userId);
        } else {
            // Jika ada peran lain, bisa ditambahkan di sini
            $penawaran = $this->penawaranModel->getAllPenawaran();
        }

        // Kirim data ke view dashboard
        return view('dashboard', ['penawaran' => $penawaran]);
    }

    public function cancel($id)
    {
        if (session()->get('role') === 'marketing') {
            // Cek penawaran berdasarkan ID dan user_id
            $penawaran = $this->penawaranModel->where('id', $id)
                ->where('user_id', session()->get('user_id')) // Ensure to match the session key
                ->first();

            if ($penawaran && $penawaran['status'] !== 'CANCEL') {
                // Update status menjadi 'CANCEL'
                $this->penawaranModel->update($id, ['status' => 'CANCEL']);
                return redirect()->to('dashboard')->with('message', 'Penawaran berhasil dibatalkan.');
            } else {
                return redirect()->to('dashboard')->with('error', 'Penawaran sudah dibatalkan atau tidak ditemukan.');
            }
        } else {
            return redirect()->to('dashboard')->with('error', 'Anda tidak memiliki izin untuk membatalkan penawaran ini.');
        }
    }
}
