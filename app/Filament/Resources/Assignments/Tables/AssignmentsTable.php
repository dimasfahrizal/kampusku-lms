<?php

namespace App\Filament\Resources\Assignments\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Judul Tugas')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge() // Membuat tipe (file/quiz) berbentuk badge agar lebih estetik
                    ->color(fn (string $state): string => match ($state) {
                        'quiz' => 'warning',
                        'file' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime('d M Y, H:i') // Format waktu rapi (Contoh: 29 May 2026, 18:27)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // ➕ Tambahkan tombol Edit di setiap baris tugas
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    // ➕ Tambahkan fitur hapus massal
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}