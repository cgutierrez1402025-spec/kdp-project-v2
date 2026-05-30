<?php

namespace App\Filament\Admin\Resources\Publications;

use App\Filament\Admin\Resources\Publications\Pages\CreatePublication;
use App\Filament\Admin\Resources\Publications\Pages\EditPublication;
use App\Filament\Admin\Resources\Publications\Pages\ListPublications;
use App\Filament\Admin\Resources\Publications\Schemas\PublicationForm;
use App\Filament\Admin\Resources\Publications\Tables\PublicationsTable;
use App\Models\Publication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;

    protected static ?string $recordTitleAttribute = 'format';

    public static function form(Schema $schema): Schema
    {
        return PublicationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PublicationsTable::configure($table);
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
            'index' => ListPublications::route('/'),
            'create' => CreatePublication::route('/create'),
            'edit' => EditPublication::route('/{record}/edit'),
        ];
    }
}
