<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * FUNGSI REGISTER (PENDAFTARAN AKUN)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input Data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:siswa,guru',
            'nis_nip' => 'nullable|string|unique:users,nis_nip',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // 2. Simpan Data User Baru ke Database
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Password di-encrypt
            'role' => $validated['role'],
            'nis_nip' => $validated['nis_nip'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        // 3. Buat Token Sanctum untuk User Baru
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Return Response JSON ke Flutter
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * FUNGSI LOGIN (MASUK MULTI-ROLE)
     */
   public function login(Request $request)
{
    $request->validate([
        'nis_nip' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('nis_nip', $request->nis_nip)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'NIS/NIP tidak ditemukan.',
        ], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Password salah.',
        ], 401);
    }
  // CEK STATUS AKUN
    if (!$user->is_active) {
        return response()->json([
            'success' => false,
            'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi pustakawan.',
        ], 403);
    }

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'nis_nip' => $user->nis_nip,
        ]
    ], 200);

    
}
public function updateFcmToken(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'fcm_token' => 'required|string',
    ]);

    $user = User::find($request->user_id);

    $user->update([
        'fcm_token' => $request->fcm_token,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'FCM Token berhasil disimpan.',
    ]);
}
    /**
     * FUNGSI LOGOUT (HAPUS TOKEN)
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout, token telah dihapus.',
        ], 200);
    }
}