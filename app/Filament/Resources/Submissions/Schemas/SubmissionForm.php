<?php

namespace App\Filament\Resources\Submissions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('assignment_id')
                ->relationship('assignment', 'title')
                ->label('Tugas')
                ->disabled(),
                
            Select::make('user_id')
                ->relationship('user', 'name')
                ->label('Mahasiswa')
                ->disabled(),

            FileUpload::make('file')
                ->label('File Tugas Mahasiswa')
                ->disk('public')
                ->disabled(),

            TextInput::make('score')
                ->label('Beri Nilai (0 - 100)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100),

            // 🔄 DIUBAH: Dari 'feedback' menjadi 'teacher_notes' agar sinkron dengan database
            Textarea::make('teacher_notes')
                ->label('Catatan/Feedback Dosen')
                ->placeholder('Masukkan feedback atau evaluasi tugas di sini...')
                ->columnSpanFull(),
        ]);
    }
}