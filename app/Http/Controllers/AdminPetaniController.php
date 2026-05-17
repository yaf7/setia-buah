<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPetaniController extends Controller
{
    public function edit(User $user)
    {
        $this->ensurePetani($user);

        return view('admin.petani.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensurePetani($user);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $payload = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->password);
        }

        $user->update($payload);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Petani berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensurePetani($user);

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Akun Petani berhasil dihapus.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petani',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Petani berhasil ditambahkan.');
    }

    private function ensurePetani(User $user): void
    {
        if ($user->role !== 'petani') {
            abort(404);
        }
    }
}
