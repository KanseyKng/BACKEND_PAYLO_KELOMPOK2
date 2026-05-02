<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $primaryKey = 'id_transaksi';
    protected $table = 'transaksi';
    protected $fillable = [
        'id_user', 'jenis_transaksi', 'id_penerima', 'jumlah', 'status', 'tanggal',
    ];

    protected $casts = ['tanggal' => 'datetime',];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function penerima() {
        return $this->belongsTo(User::class, 'id_penerima', 'id_user');
    }
    





}
