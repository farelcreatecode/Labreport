<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Kategori / Jenis Fasilitas') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold uppercase tracking-wider hover:bg-indigo-700">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Nama Kategori Fasilitas</th>
                                <th class="px-6 py-3 text-center">Jumlah Fasilitas</th>
                                <th class="px-6 py-3 text-center">Total Laporan</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $category->nama_kategori }}</td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $category->facilities_count }}</td>
                                    <td class="px-6 py-4 text-center font-semibold text-indigo-600">{{ $category->complaints_count }}</td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline-block" onsubmit="return confirm('Hapus kategori ini? Data fasilitas terkait akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-400">Belum ada kategori fasilitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
