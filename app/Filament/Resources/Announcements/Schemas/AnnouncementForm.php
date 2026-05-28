<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')
                ->relationship('course', 'name')
                ->required()
                ->searchable(),
                
            TextInput::make('title')
                ->label('Judul Pengumuman')
                ->required()
                ->maxLength(255),
                
            RichEditor::make('content')
                ->label('Isi Pengumuman')
                ->required()
                ->columnSpanFull(),
        ]);
    }
}