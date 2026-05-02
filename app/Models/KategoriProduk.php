<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $primaryKey = 'id_kategori';
    protected $table = 'kategori_produk';

    protected $fillable = ['nama_kategori'];

    public function produk(){
         return $this->hasMany(Produk::class, 'id_kategori', 'id_kategori');
    }



}
