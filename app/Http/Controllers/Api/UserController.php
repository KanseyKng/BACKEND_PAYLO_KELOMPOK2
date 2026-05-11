<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //menagmbil data profil user yang login
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    //mengedit profil user dengan verifikasi pin
    //jika pin salah kasih notifikasi jika benar update data
    public function update(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'nama'   => 'sometimes|string|max:255',
            'no_hp'  => 'sometimes|unique:users,no_hp,'.$user->id_user.',id_user',
            'alamat' => 'nullable|string',
            'pin'    => 'required|digits:6',
        ]);

        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json(['message' => 'PIN salah.'], 422);
        }

        $user->update($request->only(['nama', 'no_hp', 'alamat']));
        return response()->json(['message' => 'Profil berhasil diperbarui.', 'user' => $user]);
    }

    //mengirim otp ke email user utnuk ubah password/pin
    //buat kode, hash, simpan waktu exp, kasih respon
    public function sendOtpForSensitive(Request $request)
{
    $user = $request->user();
    $otp = rand(100000, 999999);
    $user->update(['otp' => Hash::make($otp), 'otp_expiry' => Carbon::now()->addMinutes(5)]);

    // KIRIM EMAIL S UNGGUHAN
    Mail::to($user->email)->send(new OtpMail($otp));

    return response()->json(['message' => 'OTP dikirim ke email Anda.']);
}

    //mengubah pw setelah otp terverifikasi
    public function changePassword(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'otp'      => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

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

    //mengubah pin setelah otp diverifikasi
    public function changePin(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'otp' => 'required|digits:6',
            'pin' => 'required|digits:6',
        ]);

        if (!Hash::check($request->otp, $user->otp) || Carbon::now() > $user->otp_expiry) {
            return response()->json(['message' => 'OTP tidak valid atau kadaluarsa.'], 400);
        }

        $user->update([
            'pin'        => Hash::make($request->pin),
            'otp'        => null,
            'otp_expiry' => null,
        ]);
        return response()->json(['message' => 'PIN berhasil diubah.']);
    }
}