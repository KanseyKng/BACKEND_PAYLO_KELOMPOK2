<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens ;

    protected $primaryKey = 'id_user';
    protected $fillable = [ 
        'nama', 'email', 'password', 'no_hp', 'alamat', 'role', 'pin', 'status', 'otp','otp_expiry','tanggal_daftar',
    ];

    protected $hidden = [
        'password', 'pin', 'otp', 'remember_token',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    public function saldo() {
        return $this->hasOne(Saldo::class, 'id_user', 'id_user');
    }

    public function qris() {
         return $this->hasOne(Qris::class, 'id_user', 'id_user');
    }

    public function transaksi() {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }

    public function cashflow() {
        return $this->hasMany(Cashflow::class, 'id_user', 'id_user');
    }



    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
