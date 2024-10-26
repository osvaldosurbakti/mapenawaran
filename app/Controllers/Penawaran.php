<?php

namespace App\Controllers;

use App\Models\PenawaranModel;
use CodeIgniter\Controller;

class Penawaran extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // Set timezone
    }

    /**
     * Generate nomor penawaran baru berdasarkan bulan dan tahun saat ini.
     */
    private function generateNomorPenawaran()
    {
        $currentMonth = date('m'); // Mendapatkan bulan saat ini
        $currentYear = date('Y');  // Mendapatkan tahun saat ini

        // Ambil nomor penawaran terakhir dari database
        $penawaranModel = new PenawaranModel();
        $lastPenawaran = $penawaranModel->orderBy('id', 'DESC')->first();

        // Jika tidak ada penawaran sebelumnya, mulai dari nomor 1
        $nextNumber = 1;
        if ($lastPenawaran) {
            $lastNomor = $lastPenawaran['nomor'];
            list($numberPart, $monthPart, $yearPart) = explode('-', $lastNomor);

            // Jika bulan dan tahun sama, tambahkan 1 pada nomor terakhir
            if ($monthPart == $currentMonth && $yearPart == $currentYear) {
                $nextNumber = intval($numberPart) + 1;
            }
        }

        // Format nomor penawaran seperti 00001-10-2024
        return str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . '-' . $currentMonth . '-' . $currentYear;
    }

    /**
     * Store penawaran baru ke database.
     */
    public function store()
    {
        $penawaranModel = new PenawaranModel();

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kantor_cabang' => 'required',
            'jenis_penawaran' => 'required',
            'limit_penawaran' => 'required',
            'judul' => 'required',
            'keterangan' => 'required',
            'keterangan_urgency' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        // Generate nomor penawaran baru
        $nomor = $this->generateNomorPenawaran();

        // Ambil data dari form
        $data = [
            'nomor' => $nomor,
            'tanggal' => date('Y-m-d'), // Tanggal hari ini
            'status' => 'PENDING', // Status default saat ditambahkan
            'kantor_cabang' => $this->request->getPost('kantor_cabang'),
            'jenis_penawaran' => $this->request->getPost('jenis_penawaran'),
            'limit_penawaran' => $this->request->getPost('limit_penawaran'),
            'judul' => $this->request->getPost('judul'),
            'keterangan' => $this->request->getPost('keterangan'),
            'keterangan_urgency' => $this->request->getPost('keterangan_urgency'),
            'user_id' => session()->get('user_id'), // Menyimpan ID pengguna saat ini
        ];

        // Handle multiple file uploads
        $lampiranFiles = $this->request->getFiles();
        $lampiranArray = [];

        if (isset($lampiranFiles['lampiran'])) {
            foreach ($lampiranFiles['lampiran'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $fileName = $file->getRandomName(); // Generate nama file acak
                    $file->move('uploads/', $fileName); // Pindahkan file ke folder uploads
                    $lampiranArray[] = $fileName; // Simpan nama file ke array
                }
            }
        }

        // Gabungkan nama file yang diupload ke dalam string yang dipisahkan koma
        $data['lampiran'] = implode(',', $lampiranArray);

        // Simpan data ke database
        if ($penawaranModel->insert($data)) {
            return redirect()->to('/dashboard')->with('success', 'Penawaran berhasil ditambahkan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan penawaran. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form create untuk penawaran baru.
     */
    public function create()
    {
        try {
            // Generate nomor penawaran
            $nomor = $this->generateNomorPenawaran();

            // Pastikan nomor berhasil dihasilkan
            if (!$nomor) {
                throw new \Exception('Gagal menghasilkan nomor penawaran. Silakan coba lagi.');
            }

            // Load view dengan nomor penawaran
            return view('create_penawaran', ['nomor' => $nomor]);
        } catch (\Exception $e) {
            // Tangani kesalahan dan beri umpan balik kepada pengguna
            return redirect()->to('/dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan detail penawaran berdasarkan ID.
     */
    public function detail($id)
    {
        // Inisialisasi model
        $penawaranModel = new PenawaranModel();

        // Ambil detail penawaran berdasarkan ID
        $penawaran = $penawaranModel->find($id);

        if (!$penawaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penawaran tidak ditemukan.');
        }

        // Load view detail
        return view('detail_penawaran', ['penawaran' => $penawaran]);
    }

    /**
     * Membatalkan penawaran berdasarkan ID.
     */
    public function cancel($id)
    {
        $penawaranModel = new PenawaranModel();

        try {
            // Ambil detail penawaran berdasarkan ID
            $penawaran = $penawaranModel->find($id);

            if (!$penawaran || $penawaran['status'] === 'CANCEL') {
                return redirect()->to('/dashboard')->with('error', 'Penawaran ini sudah dibatalkan atau tidak ditemukan.');
            }

            // Update status penawaran menjadi 'CANCEL'
            $penawaranModel->update($id, ['status' => 'CANCEL']);

            // Redirect kembali ke dashboard dengan pesan sukses
            return redirect()->to('/dashboard')->with('success', 'Penawaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->to('/dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Mengambil keputusan terhadap penawaran berdasarkan ID.
     */
    public function decision($id)
    {
        // Pastikan role underwriting
        if (session()->get('role') !== 'underwriting') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengambil tindakan ini.');
        }

        // Inisialisasi model
        $penawaranModel = new PenawaranModel();

        // Cari penawaran berdasarkan ID
        $penawaran = $penawaranModel->find($id);

        // Pastikan penawaran ditemukan dan statusnya pending
        if (!$penawaran || $penawaran['status'] !== 'PENDING') {
            return redirect()->to('/dashboard')->with('error', 'Penawaran tidak valid atau sudah diproses.');
        }

        // Ambil data dari form
        $decision = $this->request->getPost('decision'); // approve atau reject
        $komentar = $this->request->getPost('komentar'); // komentar underwriting

        // Tentukan status berdasarkan keputusan underwriting
        $status = ($decision === 'approve') ? 'APPROVED' : 'REJECTED';

        // Update status dan komentar di database
        $penawaranModel->updatePenawaranStatus($id, $status, $komentar);

        // Redirect kembali ke dashboard dengan pesan sukses
        return redirect()->to('/dashboard')->with('success', 'Penawaran berhasil ' . ($decision === 'approve' ? 'di-approve' : 'di-reject') . '.');
    }

    /**
     * Mengevaluasi penawaran berdasarkan ID.
     */
    public function evaluate($id)
    {
        // Inisialisasi model
        $penawaranModel = new PenawaranModel();

        // Ambil penawaran berdasarkan ID
        $penawaran = $penawaranModel->find($id);

        // Jika penawaran ditemukan
        if ($penawaran) {
            // Update status menjadi 'APPROVED' atau 'REJECTED' sesuai keputusan
            $decision = $this->request->getPost('decision'); // approve atau reject
            $komentar = $this->request->getPost('komentar'); // komentar underwriting
            $status = ($decision === 'approve') ? 'APPROVED' : 'REJECTED';

            // Perbarui status penawaran
            $penawaranModel->markAsEvaluated($id, $status, $komentar);

            // Redirect kembali ke dashboard dengan pesan sukses
            return redirect()->to('/dashboard')->with('success', 'Penawaran berhasil dievaluasi.');
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Penawaran tidak ditemukan.');
        }
    }

    /**
     * Menampilkan semua penawaran yang sudah dievaluasi (history).
     */
}
