<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaSekolah extends Model
{
    use HasFactory;

    protected $table = 'siswa_sekolah';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'email_belajar',
    ];
}
