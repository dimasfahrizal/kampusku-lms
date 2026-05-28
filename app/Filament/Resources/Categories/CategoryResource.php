<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                ->columns([
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),
                    
                    TextColumn::make('slug'),
                    
                    // Menghitung berapa banyak course di kategori ini
                    TextColumn::make('courses_count')
                        ->counts('courses')
                        ->label('Total Courses'),
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        // Hanya Admin yang bisa melihat menu Kategori di navigasi sidebar
        return auth()->user()->isAdmin();
    }
}
