<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perangkat extends Model
{
    use HasFactory;

    protected $table = 'perangkat';

    protected $fillable = [
        'kode_perangkat',
        'nama_perangkat',
        'lokasi',
        'status',
    ];

    public function aduan(): HasMany
    {
        return $this->hasMany(Aduan::class, 'perangkat_id');
    }
}
