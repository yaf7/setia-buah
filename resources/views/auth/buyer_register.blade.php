<x-app-layout>
    <x-slot name="meta">
        <x-seo-meta title="Daftar Pembeli - Setia Buah" />
    </x-slot>

    <div class="max-w-md mx-auto mt-16 px-4">
        <div class="bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
            <h2 class="text-2xl font-bold text-center text-green-700 mb-8">Daftar Akun Baru</h2>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 p-4 rounded text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('buyer.register.post') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2 px-3 border">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2 px-3 border">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2 px-3 border">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Ulangi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 py-2 px-3 border">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-600 text-white rounded-md py-3 px-4 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 font-bold">
                        Daftar
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                Sudah punya akun? <a href="{{ route('buyer.login') }}" class="text-green-600 font-bold hover:underline">Login di sini</a>
            </div>
        </div>
    </div>
</x-app-layout>