<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuyerProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::guard('buyer')->user();
        return view('buyer.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('buyer')->user();

        // 1. Tambahkan validasi untuk input regional yang baru
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Simpan data ke object user
        $user->name = $request->name;
        $user->address = $request->address;
        $user->province = $request->province;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;

        // 3. Proses upload foto KTP yang diperbaiki
        if ($request->hasFile('ktp_photo')) {
            // Hapus file lama yang ada di disk public
            if ($user->ktp_photo) {
                Storage::disk('public')->delete($user->ktp_photo);
            }
            
            // Simpan file baru ke folder 'ktp' di dalam disk 'public'
            // Ini akan menghasilkan path yang rapi seperti: "ktp/namafile.jpg"
            $path = $request->file('ktp_photo')->store('ktp', 'public');
            $user->ktp_photo = $path;
        }

        $user->save();

        return redirect()->route('buyer.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}