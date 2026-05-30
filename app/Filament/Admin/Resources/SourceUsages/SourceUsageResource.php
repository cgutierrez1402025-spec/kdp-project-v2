<?php

namespace App\Filament\Admin\Resources\SourceUsages;

use App\Filament\Admin\Resources\SourceUsages\Pages\CreateSourceUsage;
use App\Filament\Admin\Resources\SourceUsages\Pages\EditSourceUsage;
use App\Filament\Admin\Resources\SourceUsages\Pages\ListSourceUsages;
use App\Filament\Admin\Resources\SourceUsages\Schemas\SourceUsageForm;
use App\Filament\Admin\Resources\SourceUsages\Tables\SourceUsagesTable;
use App\Models\SourceUsage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SourceUsageResource extends Resource
{
    protected static ?string $model = SourceUsage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $navigationLabel = 'Usos de Fuente';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SourceUsageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SourceUsagesTable::configure($table);
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
            'index' => ListSourceUsages::route('/'),
            'create' => CreateSourceUsage::route('/create'),
            'edit' => EditSourceUsage::route('/{record}/edit'),
        ];
    }
}
