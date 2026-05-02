<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashflow extends Model
{
    protected $primaryKey = 'id_cashflow';
    protected $table = 'cashflow';
    protected $fillable = [
        'id_user', 'jenis', 'kategori', 'jumlah', 'tanggal_dibuat',
    ];

    protected $casts = ['tanggal_dibuat' => 'datetime',];

    public function user() {
         return $this->belongsTo(User::class, 'id_user', 'id_user');
     }




     
}
