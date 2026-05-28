<?php

namespace App\Filament\Resources\Announcements\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Kelas/Mata Kuliah')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('title')
                    ->label('Judul Pengumuman')
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }
}