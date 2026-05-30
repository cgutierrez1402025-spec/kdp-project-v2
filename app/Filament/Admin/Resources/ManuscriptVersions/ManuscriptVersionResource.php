<?php

namespace App\Filament\Admin\Resources\ManuscriptVersions;

use App\Filament\Admin\Resources\ManuscriptVersions\Pages\CreateManuscriptVersion;
use App\Filament\Admin\Resources\ManuscriptVersions\Pages\EditManuscriptVersion;
use App\Filament\Admin\Resources\ManuscriptVersions\Pages\ListManuscriptVersions;
use App\Filament\Admin\Resources\ManuscriptVersions\Pages\ViewManuscriptVersion;
use App\Filament\Admin\Resources\ManuscriptVersions\Schemas\ManuscriptVersionForm;
use App\Filament\Admin\Resources\ManuscriptVersions\Tables\ManuscriptVersionsTable;
use App\Filament\Admin\Resources\ManuscriptVersions\Widgets\VersionTreeWidget;
use App\Models\ManuscriptVersion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManuscriptVersionResource extends Resource
{
    protected static ?string $model = ManuscriptVersion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Versiones de Manuscrito';

    protected static ?string $modelLabel = 'Versión';

    protected static ?string $pluralModelLabel = 'Versiones';

    protected static ?string $recordTitleAttribute = 'version_number';

    public static function form(Schema $schema): Schema
    {
        return ManuscriptVersionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManuscriptVersionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChaptersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManuscriptVersions::route('/'),
            'create' => CreateManuscriptVersion::route('/create'),
            'edit' => EditManuscriptVersion::route('/{record}/edit'),
            'view' => ViewManuscriptVersion::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            VersionTreeWidget::class,
        ];
    }
}
