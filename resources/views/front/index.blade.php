<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampusku - Portal Belajar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-sm p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-indigo-600">Kampusku</a>
            
            <div class="flex items-center space-x-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">Dashboard Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">Daftar</a>
                @endauth
                
                <span class="text-gray-300">|</span>
                
                <a href="/admin" class="text-sm text-gray-500 hover:text-gray-800">Login Admin</a>
            </div>
        </div>
    </nav>

    <header class="container mx-auto py-12 px-4 text-center">
        <h2 class="text-4xl font-extrabold text-gray-900">Mulai Belajar Masa Depanmu</h2>
        <p class="mt-4 text-gray-600">Akses ribuan materi kuliah dari dosen terbaik.</p>
    </header>

    <main class="container mx-auto px-4 pb-20">
        <h3 class="text-2xl font-bold mb-6">Mata Kuliah Terbaru</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($courses as $course)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-lg transition">
                <img src="{{ $course->thumbnail ? asset('storage/app/public' . $course->thumbnail) : 'https://via.placeholder.com/400x200?text=No+Image' }}" 
                     alt="{{ $course->name }}" 
                     class="h-48 w-full object-cover"
                     onerror="this.src='https://via.placeholder.com/400x200?text=Gambar+Tidak+Ditemukan'">
                <div class="p-6">
                    <span class="text-xs font-semibold text-indigo-600 uppercase">{{ $course->category->name }}</span>
                    <h4 class="text-xl font-bold mt-2">{{ $course->name }}</h4>
                    <p class="text-gray-500 mt-2 text-sm line-clamp-2">{!! $course->about !!}</p>
                    <a href="{{ route('front.details', $course->slug) }}" 
                       class="mt-4 block text-center w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                        Lihat Materi
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </main>

</body>
</html>