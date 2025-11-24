<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        // Validasi dasar untuk semua user
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,mitra',
            'terms' => 'required|accepted',
        ];

        // Tambah validasi khusus untuk mitra
        if ($request->role === 'mitra') {
            $rules['mitra_type'] = 'required|in:hotel,wisata,restoran';
            $rules['business_name'] = 'required|string|max:255';
            $rules['business_address'] = 'required|string';
        }

        $messages = [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Pilih jenis akun',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'mitra_type.required' => 'Jenis mitra harus dipilih',
            'business_name.required' => 'Nama usaha harus diisi',
            'business_address.required' => 'Alamat usaha harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->role === 'mitra' ? $request->business_address : null,
            // Note: mitra_type and business_name can be stored in a separate mitra_profiles table
            // or added to users table via migration if needed
        ]);

        // Auto login setelah register (opsional)
        // Auth::login($user);

        // Redirect berdasarkan role
        if ($request->role === 'mitra') {
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda akan diverifikasi oleh admin dalam 1-2 hari kerja.');
        } else {
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
        }
    }
}