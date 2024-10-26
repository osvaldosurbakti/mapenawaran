<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

date_default_timezone_set('Asia/Jakarta'); // Atur ke timezone yang sesuai

class Config extends BaseConfig
{
    public $permittedURIChars = 'a-z 0-9~%.:_\-'; // Sesuaikan karakter yang diizinkan

}
