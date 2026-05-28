<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Belajar - {{ $user->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background-color: white; color: black; }
        }
    </style>
</head>
<body class="bg-gray-50 py-12 px-6 sm:px-12">

    <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-200 rounded-xl shadow-sm">
        
        <div class="no-print flex justify-between items-center mb-8 bg-gray-100 p-4 rounded-lg">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center">
                ← Kembali ke Dashboard
            </a>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2 rounded shadow transition">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>

        <div class="text-center border-b-2 border-gray-900 pb-6 mb-6">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 uppercase">Kampusku E-Learning</h1>
            <p class="text-gray-600 mt-1">Sistem Informasi Akademik & Kemajuan Belajar Mahasiswa</p>
            <p class="text-xs text-gray-400 mt-2">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8 text-sm">
            <div>
                <p class="text-gray-500 font-medium">Nama Mahasiswa</p>
                <p class="text-base font-bold text-gray-800">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Email Terdaftar</p>
                <p class="text-base font-bold text-gray-800">{{ $user->email }}</p>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-900 mb-4">Daftar Mata Kuliah Yang Diikuti</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border text-left text-sm">
                <thead class="bg-gray-50 font-semibold text-gray-700 uppercase tracking-wider text-xs border-b">
                    <tr>
                        <th class="px-6 py-3 w-12 text-center">No</th>
                        <th class="px-6 py-3">Nama Mata Kuliah</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-gray-800">
                    @forelse($courses as $index => $course)
                        <tr>
                            <td class="px-6 py-4 text-center font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $course->name }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded">{{ $course->category->name }}</span></td>
                            <td class="px-6 py-4 text-center"><span class="text-green-600 font-semibold">● Aktif Belajar</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada mata kuliah yang diambil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-12 flex justify-end text-sm">
            <div class="text-center w-48">
                <p class="text-gray-500 mb-16">Sistem Kampusku Automasi,</p>
                <div class="border-b border-gray-400 w-full mx-auto"></div>
                <p class="mt-2 font-bold text-gray-800">VALID / VERIFIED</p>
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Memberikan jeda sedikit agar CSS ter-load sempurna sebelum jendela cetak muncul
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>