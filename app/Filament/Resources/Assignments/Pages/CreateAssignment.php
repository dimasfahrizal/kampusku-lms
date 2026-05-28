<?php

namespace App\Filament\Resources\Assignments\Pages;

use App\Filament\Resources\Assignments\AssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignment extends CreateRecord
{
    protected static string $resource = AssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        // Setelah sukses klik create, dosen langsung kembali ke halaman tabel utama
        return $this->getResource()::getUrl('index');
    }
}