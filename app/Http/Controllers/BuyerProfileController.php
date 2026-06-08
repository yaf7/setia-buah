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

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'ktp_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->address = $request->address;

        if ($request->hasFile('ktp_photo')) {
            if ($user->ktp_photo) {
                Storage::delete($user->ktp_photo);
            }
            $path = $request->file('ktp_photo')->store('public/ktp');
            $user->ktp_photo = $path;
        }

        $user->save();

        return redirect()->route('buyer.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
