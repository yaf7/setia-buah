<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Profil Pembeli - Setia Buah" />
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-green-50 to-white py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Profil Saya</h1>
                    <p class="text-gray-600 mt-2">Kelola informasi pribadi dan alamat pengiriman Anda.</p>
                </div>
                <div>
                    <a href="{{ route('buyer.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md p-6 sm:p-8">
                <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" required>
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" value="{{ $user->email }}" class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed" disabled>
                            <p class="text-xs text-gray-500">Email tidak dapat diubah.</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Alamat Pengiriman Default</label>
                        <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" required>{{ old('address', $user->address) }}</textarea>
                        @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Unggah Foto KTP</label>
                        @if($user->ktp_photo)
                            <div class="mb-3">
                                <img src="{{ Storage::url($user->ktp_photo) }}" alt="KTP" class="h-32 object-contain rounded-lg border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="ktp_photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                        @error('ktp_photo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
