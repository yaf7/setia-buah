<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Form Quality Control (QC)
        </h1>

        {{-- Informasi Petani --}}
        <div class="bg-gray-50 p-4 rounded-lg mb-6 border">
            <h3 class="font-bold text-gray-700 mb-2">
                Informasi Petani
            </h3>

            <p>
                Nama Petani:
                <span class="font-medium">{{ $product->user->name }}</span>
            </p>

            <p>
                Buah:
                <span class="font-medium">{{ $product->fruit_type }}</span>
            </p>

            <p>
                Grade Estimasi Petani:
                <span class="font-medium">{{ $product->grade }}</span>
            </p>

            <p>
                Berat Estimasi Petani:
                <span class="font-medium">
                    {{ number_format($product->estimated_weight_kg, 2) }} Kg
                </span>
            </p>

            @if(isset($product->price_per_kg))
                <p>
                    Harga Beli Petani:
                    <span class="font-medium text-green-600">
                        Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}/Kg
                    </span>
                </p>
            @endif
        </div>

        {{-- Form QC --}}
        <form
            action="{{ route('admin.qc.store', $product) }}"
            method="POST"
            class="bg-white p-6 rounded-lg shadow space-y-5"
        >
            @csrf

            <input
                type="hidden"
                name="petani_product_id"
                value="{{ $product->id }}"
            >

            {{-- Berat Aktual --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Berat Aktual Timbangan (Kg)
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="actual_weight_kg"
                    value="{{ old('actual_weight_kg', $product->estimated_weight_kg) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3"
                    required
                >

                @error('actual_weight_kg')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Grade Final --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Grade Final (Hasil QC)
                </label>

                <select
                    name="final_grade"
                    class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3"
                    required
                >
                    <option value="A" {{ old('final_grade', $product->grade) == 'A' ? 'selected' : '' }}>
                        Grade A
                    </option>

                    <option value="B" {{ old('final_grade', $product->grade) == 'B' ? 'selected' : '' }}>
                        Grade B
                    </option>

                    <option value="C" {{ old('final_grade', $product->grade) == 'C' ? 'selected' : '' }}>
                        Grade C
                    </option>
                </select>

                @error('final_grade')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Harga --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @if(isset($product->price_per_kg))
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Harga Beli Petani (Rp/Kg)
                        </label>

                        <input
                            type="text"
                            value="Rp {{ number_format($product->price_per_kg, 0, ',', '.') }}"
                            class="mt-1 block w-full rounded-md bg-gray-100 border-gray-300 py-2.5 px-3"
                            readonly
                        >
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Harga Jual Final (Rp/Kg)
                    </label>

                    <input
                        type="number"
                        name="final_price_per_kg"
                        min="0"
                        step="100"
                        value="{{ old('final_price_per_kg') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 py-2.5 px-3"
                        placeholder="Contoh: 35000"
                        required
                    >

                    <p class="mt-1 text-xs text-gray-500">
                        Harga yang akan ditampilkan pada katalog.
                    </p>

                    @error('final_price_per_kg')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <input
                type="hidden"
                name="status"
                value="accepted"
            >

            {{-- Catatan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Catatan QC (Opsional)
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 p-3"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end pt-4 gap-2">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium"
                >
                    Simpan Hasil QC
                </button>
            </div>
        </form>
    </div>
</x-app-layout>