<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PenawaranModel;

class History extends Controller
{
    public function index()
    {
        $penawaranModel = new PenawaranModel();
        $role = session()->get('role');
        $userId = session()->get('user_id');

        // Initialize the query
        $query = $penawaranModel->whereIn('status', ['APPROVED', 'REJECTED']); // Exclude CANCEL by default

        if ($role === 'marketing') {
            $query->where('user_id', $userId); // Marketing hanya bisa melihat penawaran mereka sendiri
        } elseif ($role === 'underwriting') {
            // Underwriting can see all offers except canceled ones
            // No additional condition needed as 'CANCEL' is already excluded
        }

        // Include the CANCEL status only for marketing users
        if ($role === 'marketing') {
            $query->orWhere('status', 'CANCEL');
        }

        $data['history'] = $query->findAll();

        return view('history', [
            'history' => $data['history'],
            'search' => ''
        ]);
    }
}
