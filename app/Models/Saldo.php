<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    protected $primaryKey = 'id_saldo';
    protected $table = 'saldo';
    protected $fillable = ['id_user', 'jumlah_saldo'];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }


}
