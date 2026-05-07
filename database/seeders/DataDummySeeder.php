<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cashflow;
use Carbon\Carbon;
use App\Models\Saldo;
use App\Models\KategoriProduk;
use App\Models\UMKM;
use App\Models\Produk;
use App\Models\Edukasi;
use App\Models\PengaturanSistem;
use Illuminate\Support\Facades\Hash;

class DataDummySeeder extends Seeder
{
    public function run()
    {
        // Kategori
        $makanan = KategoriProduk::create(['nama_kategori' => 'Makanan Khas']);
        $kerajinan = KategoriProduk::create(['nama_kategori' => 'Kerajinan']);

        // Admin
        $admin = User::create([
            'nama' => 'Admin', 'email' => 'admin@paylo.id', 'password' => Hash::make('admin123'),
            'no_hp' => '0800000000', 'role' => 'super_admin', 'status' => 'active', 'pin' => Hash::make('000000')
        ]);
        Saldo::create(['id_user' => $admin->id_user, 'jumlah_saldo' => 0]);

        // Pelanggan
        $budi = User::create([
            'nama' => 'Budi', 'email' => 'budi@mail.com', 'password' => Hash::make('password'),
            'no_hp' => '08123456789', 'role' => 'pelanggan/turis', 'status' => 'active', 'pin' => Hash::make('123456')
        ]);

        // Ambil user Budi
$budi = User::where('email', 'budi@mail.com')->first();

Cashflow::create([
    'id_user' => $budi->id_user,
    'jenis' => 'Pemasukan',
    'kategori' => 'Top-Up',
    'jumlah' => 100000,
    'tanggal_dibuat' => Carbon::now()->subDays(2),
]);
Cashflow::create([
    'id_user' => $budi->id_user,
    'jenis' => 'Pengeluaran',
    'kategori' => 'Transfer Keluar',
    'jumlah' => 30000,
    'tanggal_dibuat' => Carbon::now()->subDay(),
]);



        Saldo::create(['id_user' => $budi->id_user, 'jumlah_saldo' => 100000]);

        // UMKM Makanan
        $umkm1 = UMKM::create([
            'nama_umkm' => 'Warung Bakso Malang', 'alamat' => 'Jl. Ahmad Yani 45, Malang', 'no_hp' => '081111111',
            'deskripsi' => 'Bakso urat legendaris.', 'link_lokasi_umkm' => 'https://goo.gl/maps/xxxx',  'rating' => '4'
        ]);
        Produk::create([
            'id_umkm' => $umkm1->id_umkm, 'nama_produk' => 'Bakso Urat', 'gambar' => 'https://picsum.photos/400?random=1',
            'harga' => 20000, 'deskripsi' => 'Bakso sapi kenyal.', 'id_kategori' => $makanan->id_kategori
        ]);

        // Edukasi
        Edukasi::create(['judul' => 'Mengenal QRIS', 'isi_edukasi' => 'QRIS adalah standar pembayaran...']);
        Edukasi::create(['judul' => 'Tips Wisata Hemat', 'isi_edukasi' => 'Atur anggaran dengan PAYLO...']);

        // Pengaturan
        PengaturanSistem::create(['batas_transfer' => 1000000, 'biaya_admin' => 0, 'nama_aplikasi' => 'PAYLO']);
    }
}