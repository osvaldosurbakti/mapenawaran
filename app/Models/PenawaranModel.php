<?php

namespace App\Models;

use CodeIgniter\Model;

class PenawaranModel extends Model
{
    protected $table = 'penawaran';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nomor',
        'tanggal',
        'status',
        'kantor_cabang',
        'jenis_penawaran',
        'limit_penawaran',
        'judul',
        'keterangan',
        'keterangan_urgency',
        'lampiran',
        'komentar',
        'user_id', // Pastikan field user_id ada di sini
    ];

    // Fungsi untuk mendapatkan penawaran berdasarkan user
    public function getPenawaranByUser($userId)
    {
        return $this->where('user_id', $userId)
            ->whereNotIn('status', ['REJECTED', 'APPROVED', 'CANCEL'])
            ->findAll();
    }

    // Fungsi untuk mendapatkan penawaran yang relevan untuk marketing
    public function getRelevantPenawaranForMarketing()
    {
        return $this->whereNotIn('status', ['APPROVED', 'REJECTED', 'CANCEL'])
            ->findAll();
    }

    // Fungsi untuk mendapatkan penawaran dengan status tertentu
    public function getPenawaranByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    // Fungsi untuk memperbarui status dan komentar penawaran
    public function updatePenawaranStatus($id, $status, $komentar)
    {
        try {
            return $this->update($id, [
                'status' => $status,
                'komentar' => $komentar,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal memperbarui status penawaran: ' . $e->getMessage());
        }
    }

    // Fungsi untuk mendapatkan penawaran berdasarkan ID dan status
    public function getPenawaranByIdAndStatus($id, $status)
    {
        return $this->where('id', $id)->where('status', $status)->first();
    }

    // Fungsi untuk menambahkan penawaran baru
    public function addPenawaran($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menambahkan penawaran: ' . $e->getMessage());
        }
    }

    // Fungsi untuk menghapus penawaran (soft delete)
    public function deletePenawaran($id)
    {
        return $this->delete($id);
    }

    // Fungsi untuk mengambil semua penawaran
    public function getAllPenawaran()
    {
        return $this->whereNotIn('status', ['REJECTED', 'APPROVED', 'CANCEL'])->findAll();
    }

    // Fungsi untuk mendapatkan penawaran yang sudah dievaluasi (untuk underwriting)
    public function getEvaluatedPenawaran()
    {
        return $this->whereIn('status', ['APPROVED', 'REJECTED'])->findAll();
    }

    // Fungsi untuk menandai penawaran sebagai dievaluasi dan mengubah statusnya
    public function markAsEvaluated($id, $status, $komentar)
    {
        return $this->update($id, [
            'status' => $status,
            'komentar' => $komentar,
        ]);
    }

    // Fungsi untuk mendapatkan riwayat penawaran berdasarkan ID marketing
    // Fungsi untuk mendapatkan riwayat penawaran berdasarkan ID marketing
    public function getHistoryPenawaranByMarketingId($userId)
    {
        return $this->whereIn('status', ['APPROVED', 'REJECTED', 'CANCEL']) // Menyertakan 'CANCEL'
            ->where('user_id', $userId) // Asumsikan Anda memiliki field user_id untuk melacak pengguna yang membuat tawaran
            ->findAll();
    }

    // Fungsi untuk memindahkan penawaran yang dibatalkan ke tabel riwayat
    public function moveToHistory($id)
    {
        // Ambil data penawaran yang dibatalkan
        $penawaran = $this->find($id);

        if (!$penawaran) {
            throw new \Exception('Penawaran tidak ditemukan.');
        }

        // Set status menjadi CANCEL
        $penawaran['status'] = 'CANCEL';

        // Pastikan untuk tidak menyertakan kolom yang tidak ada di tabel riwayat
        unset($penawaran['created_by']); // Hapus 'created_by' jika tidak ada di tabel history

        // Simpan penawaran ke tabel riwayat
        $this->db->table('history')->insert($penawaran);

        // Hapus penawaran dari tabel penawaran
        return $this->delete($id);
    }

    // Fungsi untuk membatalkan penawaran
    public function cancelPenawaran($id)
    {
        try {
            // Pindahkan penawaran ke riwayat
            return $this->moveToHistory($id);
        } catch (\Exception $e) {
            // Tangani kesalahan database
            throw new \Exception('Terjadi kesalahan saat mengakses database: ' . $e->getMessage());
        }
    }
}
