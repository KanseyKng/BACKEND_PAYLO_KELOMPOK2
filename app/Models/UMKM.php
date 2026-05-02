<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UMKM extends Model
{
    protected $primaryKey = 'id_umkm';
    protected $table = 'umkm';

    protected $fillable = [
        'nama_umkm',
        'alamat',
        'no_hp',
        'deskripsi',
        'link_lokasi_umkm',
        'rating',
        'tanggal_dibuat',
    ];

    protected $casts = ['tanggal_dibuat' => 'datetime',];

    public function produk() {
        return $this->hasMany(Produk::class, 'id_umkm', 'id_umkm');
    }
}
