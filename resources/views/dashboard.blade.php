<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="mb-8 p-5 bg-gray-50 border border-gray-200 rounded-xl">
                    <form action="{{ route('dashboard.join-class') }}" method="POST" class="flex flex-col sm:flex-row items-end gap-4">
                        @csrf
                        <div class="flex-1 w-full">
                            <label for="invite_code" class="block text-sm font-semibold text-gray-700 mb-1">🔑 Punya Kode Kelas? Gabung di Sini</label>
                            <input type="text" name="invite_code" id="invite_code" placeholder="Contoh: AB12CD" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase font-mono tracking-widest text-center sm:text-left" required>
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm shadow-sm transition duration-150 whitespace-nowrap">
                            Gabung Kelas
                        </button>
                    </form>
                </div>

                <div class="mb-8 border-b pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600 mt-1">Berikut adalah daftar mata kuliah yang sedang kamu pelajari.</p>
                    </div>
                    <a href="{{ route('dashboard.report') }}" class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white font-semibold px-4 py-2.5 rounded-lg text-sm shadow-sm">
                        Cetak Laporan Belajar
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center shadow-sm">
                        <div class="p-3 bg-indigo-600 text-white rounded-lg mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Kursus Diikuti</p>
                            <p class="text-xl font-bold text-gray-900">{{ $courses->count() }} Kursus</p>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-center shadow-sm">
                        <div class="p-3 bg-blue-600 text-white rounded-lg mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Nilai Rata-Rata</p>
                            <p class="text-xl font-bold text-blue-600">{{ $averageScore }}</p>
                        </div>
                    </div>

                    <div class="p-4 bg-green-50 border border-green-100 rounded-xl shadow-sm">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Progress Penyelesaian</p>
                        <p class="text-xl font-bold text-green-600 mb-2">{{ $progressPercentage }}%</p>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-4">📚 Mata Kuliah Kamu</h3>
                @if($courses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        @foreach($courses as $course)
                            <div class="border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col bg-gray-50">
                                <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : 'https://via.placeholder.com/400x200?text=No+Image' }}" class="h-40 w-full object-cover border-b border-gray-200" alt="{{ $course->name }}">
                                <div class="p-5 flex-1 flex flex-col">
                                    <span class="text-xs font-semibold text-indigo-600 uppercase mb-1">{{ $course->category->name ?? 'Umum' }}</span>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $course->name }}</h4>
                                    <div class="mt-auto pt-4">
                                        <a href="{{ route('front.details', $course->slug) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                                            Lanjut Belajar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-indigo-50 border border-indigo-100 p-8 rounded-xl text-center mb-8">
                        <p class="text-indigo-800 font-medium text-lg mb-4">Kamu belum mengambil mata kuliah apapun.</p>
                    </div>
                @endif

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-8">
                    <h3 class="text-md font-bold text-gray-900 mb-4">🎯 Tugas & Kuis yang Harus Dikerjakan</h3>
                    @if($pendingAssignments->isEmpty())
                        <div class="p-4 text-sm text-green-700 bg-green-50 rounded-lg text-center font-medium">
                            🎉 Luar biasa! Semua tugas dan kuis telah selesai dikerjakan.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="px-6 py-3">Mata Kuliah</th>
                                        <th class="px-6 py-3">Judul Tugas</th>
                                        <th class="px-6 py-3">Tipe</th>
                                        <th class="px-6 py-3">Deadline</th>
                                        <th class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-gray-600">
                                    @foreach($pendingAssignments as $assignment)
                                        <tr>
                                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $assignment->course->name }}</td>
                                            <td class="px-6 py-4">{{ $assignment->title }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-0.5 text-xs rounded font-semibold {{ $assignment->type === 'quiz' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $assignment->type === 'quiz' ? 'Kuis' : 'Tugas File' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-red-600 font-medium">
                                                {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('dashboard.assignment.show', $assignment->id) }}" class="text-indigo-600 font-bold hover:text-indigo-900">
                                                    Kerjakan &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h3 class="text-md font-bold text-gray-900 mb-4">📝 Riwayat Nilai & Feedback Pengumpulan Tugas</h3>
                    @if($studentSubmissions->isEmpty())
                        <div class="p-4 text-sm text-gray-500 bg-white border rounded-lg text-center font-medium">
                            Belum ada tugas yang kamu kumpulkan.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border bg-white">
                            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                                <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-bold">
                                    <tr>
                                        <th class="px-6 py-3">Nama Tugas</th>
                                        <th class="px-6 py-3">Tanggal Kumpul</th>
                                        <th class="px-6 py-3">Status / Nilai</th>
                                        <th class="px-6 py-3">Catatan Dosen</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 text-gray-600">
                                    @foreach($studentSubmissions as $submission)
                                        <tr>
                                            <td class="px-6 py-4 font-semibold text-gray-900">
                                                {{ $submission->assignment->title ?? 'Tugas Terhapus' }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y, H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4">
                                                @if(is_null($submission->score))
                                                    <span class="px-2 py-1 text-xs rounded-full font-semibold bg-amber-100 text-amber-800">
                                                        ⏳ Menunggu Penilaian
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 text-sm rounded-lg font-bold shadow-sm
                                                        {{ $submission->score >= 80 ? 'bg-green-100 text-green-800' : ($submission->score >= 60 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                        ★ {{ $submission->score }} / 100
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm italic text-gray-500">
                                                {{ $submission->teacher_notes ?? 'Tidak ada catatan khusus.' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>