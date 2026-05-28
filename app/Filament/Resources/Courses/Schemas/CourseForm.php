<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                
            TextInput::make('slug')->disabled()->dehydrated()->required(),

            Select::make('category_id')
                ->relationship('category', 'name')
                ->required()
                ->searchable(),

            RichEditor::make('about')->required()->columnSpanFull(),

            FileUpload::make('thumbnail')
                ->image()
                ->disk('public')
                ->directory('courses-thumbnails')
                ->required(),

            // 🌟 Fitur Pilih Dosen Pengajar (Many-to-Many lewat Pivot)
            Select::make('users')
                ->relationship(
                    name: 'users', 
                    titleAttribute: 'name',
                    modifyQueryUsing: fn ($query) => $query->where('role', 'dosen') 
                )
                ->multiple()
                ->preload()
                ->label('Dosen Pengajar')
                ->placeholder('Pilih satu atau beberapa dosen...'),
        ]);
    }
}