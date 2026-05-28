<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lembar Kerja ' . ($assignment->type === 'quiz' ? 'Kuis' : 'Tugas')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <span class="px-2 py-1 text-xs rounded font-semibold bg-indigo-100 text-indigo-800 uppercase">
                        {{ $assignment->course->name }}
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $assignment->title }}</h3>
                    <p class="text-sm text-red-600 font-medium mt-1">
                        ⏰ Batas Pengumpulan: {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="mb-8 p-4 bg-gray-50 rounded-xl border">
                    <h4 class="font-bold text-gray-800 mb-2">Instructions / Deskripsi:</h4>
                    <div class="text-gray-600 prose max-w-none">
                        {{-- 💡 PERBAIKAN: Menggunakan description sesuai model Assignment --}}
                        {!! $assignment->description !!} 
                    </div>
                </div>

                @if($assignment->type === 'quiz')
                    <form action="{{ route('dashboard.assignment.submit-quiz', $assignment->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        @if(!empty($assignment->quiz_questions))
                            @foreach($assignment->quiz_questions as $index => $quiz)
                                <div class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm space-y-3">
                                    {{-- 💡 Menggunakan $quiz['question'] --}}
                                    <p class="font-bold text-gray-800">
                                        {{ $index + 1 }}. {{ $quiz['question'] ?? 'Pertanyaan tidak ditemukan' }}
                                    </p>
                                    
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach(['a', 'b', 'c', 'd'] as $optionKey)
                                            @if(isset($quiz[$optionKey]))
                                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                    {{-- Value radio menggunakan huruf kecil (a, b, c, d) --}}
                                                    <input type="radio" name="answers[{{ $index }}]" value="{{ $optionKey }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500" required>
                                                    <span class="ml-3 text-sm text-gray-700">
                                                        <strong class="text-indigo-600 uppercase">{{ $optionKey }}.</strong> {{ $quiz[$optionKey] }}
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg text-sm">
                                ⚠️ Dosen belum menginput butir soal pilihan ganda untuk kuis ini.
                            </div>
                        @endif

                        <div class="flex justify-between items-center pt-4 border-t">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline text-sm font-medium">&larr; Kembali</a>
                            @if(!empty($assignment->quiz_questions))
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-2 rounded-lg text-sm shadow-sm transition">
                                    Selesai & Kirim Kuis
                                </button>
                            @endif
                        </div>
                    </form>

                @else
                    <form action="{{ route('dashboard.assignment.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Unggah File Jawaban (PDF, DOCX, ZIP - Max 5MB)</label>
                            <input type="file" name="file_submission" required class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2 focus:outline-none">
                            @error('file_submission') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline text-sm font-medium">&larr; Kembali ke Dashboard</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-lg text-sm shadow-sm transition">
                                Kirim Jawaban File
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>