<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail'),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),

                // 🎯 Kolom Kode Undangan Kelas
                TextColumn::make('invite_code')
                    ->label('Kode Undangan')
                    ->searchable()
                    ->copyable() 
                    ->fontFamily('mono')
                    ->badge()
                    ->color('info')
                    ->placeholder('KOSONG'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // 💡 Kita panggil langsung jalurnya di sini agar bebas dari error import
                \Filament\Actions\EditAction::make(), 
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}