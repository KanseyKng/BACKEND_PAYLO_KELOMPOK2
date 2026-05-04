<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qris extends Model
{
    protected $primaryKey = 'id_qris';
    protected $table = 'qris';
    protected $fillable = ['id_user', 'kode_qr'];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
}
