<?php

namespace App\Filament\Resources\Assignments;

use App\Filament\Resources\Assignments\Tables\AssignmentsTable;
use App\Filament\Resources\Assignments\Pages\CreateAssignment;
use App\Filament\Resources\Assignments\Pages\EditAssignment;
use App\Filament\Resources\Assignments\Pages\ListAssignments;
use App\Filament\Resources\Assignments\Schemas\AssignmentForm;
use App\Models\Assignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssignmentResource extends Resource
{
    protected static ?string $model = Assignment::class; 

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
        'index' => Pages\ListAssignments::route('/'),
        'create' => Pages\CreateAssignment::route('/create'),
        'edit' => Pages\EditAssignment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        // Jika user adalah admin, biarkan melihat semua data tugas
        if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return parent::getEloquentQuery();
        }

        // Mengambil semua ID kelas yang diajar oleh dosen ini
        $courseIds = $user->courses->pluck('id')->toArray();
        
        // 🎯 PERBAIKAN: Gunakan whereIn karena $courseIds berbentuk ARRAY
        return parent::getEloquentQuery()->whereIn('course_id', $courseIds);
    }
}
