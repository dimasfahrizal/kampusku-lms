<?php

namespace App\Filament\Resources\Modules;

use App\Filament\Resources\Modules\Pages\CreateModule;
use App\Filament\Resources\Modules\Pages\EditModule;
use App\Filament\Resources\Modules\Pages\ListModules;
use App\Filament\Resources\Modules\Schemas\ModuleForm;
use App\Filament\Resources\Modules\Tables\ModulesTable;
use App\Models\Module;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
                ->components([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->required()
                        ->disabled()
                        ->dehydrated(),

                    TextInput::make('video_url')
                        ->label('Video URL (YouTube)')
                        ->url()
                        ->required(),

                    Select::make('course_id')
                        ->relationship('course', 'name') // Menghubungkan modul ke Course
                        ->required()
                        ->searchable(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                ->columns([
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),

                    TextColumn::make('course.name')
                        ->label('Mata Kuliah')
                        ->sortable(),

                    TextColumn::make('video_url')
                        ->limit(30), // Biar link panjang tidak menabrak kolom lain
                ]);
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
            'index' => ListModules::route('/'),
            'create' => CreateModule::route('/create'),
            'edit' => EditModule::route('/{record}/edit'),
        ];
    }
}
