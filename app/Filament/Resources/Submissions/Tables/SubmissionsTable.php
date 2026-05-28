<?php

namespace App\Filament\Resources\Submissions\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assignment.title')
                    ->label('Tugas/Kuis')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                TextColumn::make('score')
                    ->label('Nilai')
                    ->badge()
                    ->color(fn (mixed $state): string => match (true) {
                        (float)$state >= 80 => 'success', // Hijau jika nilai >= 80
                        (float)$state >= 60 => 'warning', // Kuning jika nilai cukup
                        default => 'danger',              // Merah jika nilai kurang
                    })
                    // Menampilkan teks ini jika mahasiswa belum diberi nilai (kolom masih NULL)
                    ->placeholder('Belum Dinilai'), 

                TextColumn::make('created_at')
                    ->label('Dikumpul Pada')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                EditAction::make() // 🎯 Menampilkan tombol "Edit" agar dosen bisa mengisi nilai & catatan
                    ->label('Beri Nilai'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }
}