<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Nama tabel user
    protected $primaryKey = 'id';
    protected $allowedFields = ['email', 'password', 'role'];
}
