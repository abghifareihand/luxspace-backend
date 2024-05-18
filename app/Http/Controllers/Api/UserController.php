<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Validasi data masukan
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6',
        ]);

        // Jika validasi gagal, kembalikan respons JSON dengan pesan kesalahan dan kode status 422 (Unprocessable Entity)
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Buat pengguna baru jika validasi berhasil
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'USER',
        ]);

        // Buat token otentikasi untuk pengguna baru
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Register user success',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // pengecekan email
        if (!$user) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Invalid email',
            ], 401);
        }

        // pengecekan password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Invalid password',
            ], 401);
        }

        // token autentikasi
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Login user success',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }



    public function fetch(Request $request)
    {
        // Mendapatkan pengguna yang sedang terautentikasi
        $user = $request->user();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Get user success',
            'data' => $user
        ]);
    }

    public function update(Request $request)
    {
        // Dapatkan pengguna yang sedang terotentikasi
        $user = $request->user();

        // Validasi data masukan
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:100',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:email,password', // Hanya diperlukan jika ada perubahan email atau password
            'password' => 'sometimes|required_with:current_password|nullable|min:6|confirmed', // Hanya diperlukan jika ada perubahan password
            'password_confirmation' => 'sometimes|required_with:password|nullable' // Field untuk konfirmasi password
        ]);

        // Jika validasi gagal, kembalikan respons JSON dengan pesan kesalahan dan kode status 422 (Unprocessable Entity)
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Memeriksa keberadaan permintaan perubahan email
        if ($request->has('email')) {
            // Verifikasi kata sandi saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 401);
            }

            // Jika kata sandi saat ini benar, lanjutkan ke perubahan alamat email
            $user->email = $request->email;
        }

        // Memeriksa keberadaan permintaan perubahan password
        if ($request->has('password')) {
            // Verifikasi kata sandi saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 401);
            }
            // Ubah kata sandi baru
            $user->password = Hash::make($request->password);
        }

        // Update informasi profil pengguna
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        $user->save();

        // Kembalikan respons JSON dengan kode status 200 (OK) dan data pengguna yang telah diperbarui
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Update user success',
            'data' => $user
        ]);
    }


    public function logout(Request $request)
    {
        // Mendapatkan pengguna yang sedang terautentikasi
        $user = $request->user();

        // Menghapus semua token autentikasi pengguna
        $user->tokens()->delete();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Logout success',
        ]);
    }
}
