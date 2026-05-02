<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $primaryKey = 'id_produk';
    protected $table = 'produk';

    protected $fillable = [
        'id_umkm',
        'nama_produk',
        'gambar',
        'harga',
        'deskripsi',
        'id_kategori',
    ];

    public function umkm() {
        return $this->belongsTo(UMKM::class, 'id_umkm', 'id_umkm');
    }

    public function kategori(){
         return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id_kategori');
    }









}
