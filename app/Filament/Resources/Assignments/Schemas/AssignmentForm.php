<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;

class AssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')
                ->relationship('course', 'name')
                ->required()
                ->searchable(),
                
            TextInput::make('title')
                ->required()
                ->maxLength(255),
                
            DateTimePicker::make('deadline')
                ->required(),
                
            Select::make('type')
                ->options([
                    'file' => 'Upload File Tugas',
                    'quiz' => 'Kuis Pilihan Ganda',
                ])
                ->required()
                ->reactive(),
                
            FileUpload::make('attachment')
                ->label('File Dokumen Tugas (Opsional)')
                ->disk('public')
                ->directory('assignments')
                ->visible(fn (callable $get) => $get('type') === 'file'),

            MarkdownEditor::make('description')
                ->required()
                ->columnSpanFull(),

            Repeater::make('quiz_questions')
                ->label('Daftar Soal Kuis')
                ->schema([
                    Textarea::make('question')->label('Pertanyaan')->required(),
                    TextInput::make('a')->label('Pilihan A')->required(),
                    TextInput::make('b')->label('Pilihan B')->required(),
                    TextInput::make('c')->label('Pilihan C')->required(),
                    TextInput::make('d')->label('Pilihan D')->required(),
                    Select::make('correct_answer')
                        ->label('Kunci Jawaban Benar')
                        ->options([
                            'a' => 'A',
                            'b' => 'B',
                            'c' => 'C',
                            'd' => 'D',
                        ])->required(),
                ])
                ->visible(fn (callable $get) => $get('type') === 'quiz')
                ->columnSpanFull(),
        ]);
    }
}