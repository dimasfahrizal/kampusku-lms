<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->name }} - Kampusku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm p-4 mb-8">
        <div class="container mx-auto">
            <a href="/" class="text-indigo-600 font-bold hover:underline">← Kembali ke Daftar Kursus</a>
        </div>
    </nav>

    <main class="container mx-auto px-4 pb-20">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex flex-col md:flex-row gap-8">
                <img src="{{ asset('storage/app/public' . $course->thumbnail) }}" class="w-full md:w-1/3 rounded-lg object-cover h-64" alt="{{ $course->name }}">
                
                <div class="flex-1">
                    <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $course->category->name }}
                    </span>
                    <h1 class="text-3xl font-bold mt-4">{{ $course->name }}</h1>
                    <div class="prose mt-4 text-gray-600">
                        {!! $course->about !!}
                    </div>

                    <div class="mt-8">
                        @auth
                            @if(Auth::user()->courses->contains($course->id))
                                <div class="inline-flex items-center bg-green-100 border border-green-200 text-green-700 px-6 py-3 rounded-xl font-bold gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Anda Sudah Terdaftar di Kursus Ini
                                </div>
                            @else
                                <form action="{{ route('front.enroll', $course->slug) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition duration-200">
                                        Ambil Kursus Ini (Gratis)
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-gray-800 hover:bg-gray-950 text-white px-8 py-3 rounded-xl font-bold shadow-md transition duration-200">
                                Login untuk Mengambil Kursus
                            </a>
                        @endauth
                    </div>

                </div>
            </div>

            <hr class="my-10 border-gray-200">

            <h2 class="text-2xl font-bold mb-6">Daftar Modul Belajar</h2>
            
            @auth
                @if(Auth::user()->courses->contains($course->id))
                    <div class="space-y-4">
                        @forelse($course->modules as $module)
                            <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $module->name }}</h3>
                                    <p class="text-gray-500 text-sm">Materi Video</p>
                                </div>
                                <a href="{{ $module->video_url }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Tonton Video
                                </a>
                            </div> @empty
                            <p class="text-gray-500 italic p-4 bg-gray-50 rounded-lg text-center">Belum ada modul video untuk kursus ini. Dosen akan segera menambahkannya.</p>
                        @endforelse
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 p-6 rounded-xl text-center">
                        <p class="text-amber-700 font-medium">🔒 Materi dikunci. Silakan klik tombol <b>"Ambil Kursus Ini"</b> di atas terlebih dahulu untuk membuka modul belajar.</p>
                    </div>
                @endif
            @else
                <div class="bg-gray-100 p-6 rounded-xl text-center border border-gray-200">
                    <p class="text-gray-600">🔒 Silakan <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Login</a> terlebih dahulu untuk mengakses materi kuliah.</p>
                </div>
            @endauth

        </div>
    </main>
</body>
</html>