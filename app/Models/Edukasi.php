<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edukasi extends Model
{
    protected $primaryKey = 'id_edukasi';
    protected $table = 'edukasi';

    protected $fillable = [
        'judul',
        'isi_edukasi',
        'tanggal_dibuat',
    ];
    public $timestamps = false;

    protected $casts = ['tanggal_dibuat' => 'datetime',];


}
