<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $primaryKey = 'id_pengaturan';
    protected $table = 'pengaturan_sistem';

    protected $fillable = [
        'batas_transfer',
        'biaya_admin',
        'nama_aplikasi',
    ];



}
