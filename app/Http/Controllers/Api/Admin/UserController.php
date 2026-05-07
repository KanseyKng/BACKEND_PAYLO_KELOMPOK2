<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Saldo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //mengambil data user yang memiliki role pelanggan/turis
    public function index()
    {
        $users = User::where('role', 'pelanggan/turis')->paginate(20);
        return response()->json($users);
    }

    //menambah user baru(oleh admin)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|unique:users,no_hp',
            'password' => 'required|min:6',
            'alamat' => 'nullable|string',
        ]);
        $user = User::create([
            'nama' => $request->nama, 'email' => $request->email, 'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password), 'alamat' => $request->alamat,
            'role' => 'pelanggan/turis', 'status' => 'active',
        ]);
        Saldo::create(['id_user' => $user->id_user, 'jumlah_saldo' => 0]);
        return response()->json(['message' => 'User berhasil ditambahkan.', 'user' => $user], 201);
    }

    //memperbarui data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id_user.',id_user',
            'no_hp' => 'sometimes|unique:users,no_hp,'.$user->id_user.',id_user',
            'status' => 'sometimes|in:active,banned',
        ]);
        $user->update($request->only(['nama','email','no_hp','status']));
        return response()->json(['message' => 'Data user berhasil diperbarui.', 'user' => $user]);
    }
    public function show($id)
{
    $user = User::findOrFail($id);
    
    return response()->json([
        'id_user' => $user->id_user,
        'nama'    => $user->nama,
        'email'   => $user->email,
        'no_hp'   => $user->no_hp,
        'alamat'  => $user->alamat,
        'role'    => $user->role,
        'status'  => $user->status,
    ]);
}

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}