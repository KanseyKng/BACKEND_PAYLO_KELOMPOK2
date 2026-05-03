<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    //Register Akun User Pelanggan/turiss
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'no_hp'    => 'required|string|max:15|unique:users,no_hp',
            'password' => 'required|string|min:6',
            'alamat'   => 'nullable|string',
        ]);

        $user = User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'no_hp'     => $request->no_hp,
            'password'  => Hash::make($request->password), // hash password
            'alamat'    => $request->alamat,
            'role'      => 'pelanggan/turis',
            'status'    => 'active',
        ]);

        //set saldo awal 0
        Saldo::create(['id_user' => $user->id_user, 'jumlah_saldo' => 0]);

        // Buat token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil. Silakan buat PIN Anda.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    //Login userst
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        //cek email dan password apakah salah
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // Cek status banned atau tidak
        if ($user->status === 'banned') {
            return response()->json(['message' => 'Akun Anda telah diblokir.'], 403);
        }

        // Hapus token lama
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    // LOGOUT user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.']);
    }

    //mengambil data pengguna yang login dan saldo nya
    public function me(Request $request)
    {
        $user = $request->user()->load('saldo');
        return response()->json($user);
    }

    //ketika user lupa password (kirim otp)
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $user->update([
            'otp'        => Hash::make($otp),
            'otp_expiry' => Carbon::now()->addMinutes(5),
        ]);

        //otp di kirim lewat email
        \Log::info("OTP untuk {$user->email}: {$otp}");

        return response()->json(['message' => 'OTP telah dikirim ke email Anda.']);
    }

    //ketika user ingin reset password (butuh otp)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'otp'      => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->otp, $user->otp) || Carbon::now() > $user->otp_expiry) {
            return response()->json(['message' => 'OTP tidak valid atau kadaluarsa.'], 400);
        }

        $user->update([
            'password'   => Hash::make($request->password),
            'otp'        => null,
            'otp_expiry' => null,
        ]);
        return response()->json(['message' => 'Password berhasil diubah.']);
    }

    //membuat pin setelah register/di menu profil
    public function setPin(Request $request)
    {
        $user = $request->user();
        $request->validate(['pin' => 'required|digits:6']);
        $user->update(['pin' => Hash::make($request->pin)]);
        return response()->json(['message' => 'PIN berhasil dibuat.']);
    }

    //kirim ulan otp
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        $user->update(['otp' => Hash::make($otp), 'otp_expiry' => Carbon::now()->addMinutes(5)]);
        \Log::info("OTP ulang untuk {$user->email}: {$otp}");
        return response()->json(['message' => 'OTP baru telah dikirim.']);
    }
}