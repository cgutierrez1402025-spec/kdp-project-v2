<?php

namespace App\Filament\Admin\Resources\KdpSelectPeriods;

use App\Filament\Admin\Resources\KdpSelectPeriods\Pages\CreateKdpSelectPeriod;
use App\Filament\Admin\Resources\KdpSelectPeriods\Pages\EditKdpSelectPeriod;
use App\Filament\Admin\Resources\KdpSelectPeriods\Pages\ListKdpSelectPeriods;
use App\Filament\Admin\Resources\KdpSelectPeriods\Schemas\KdpSelectPeriodForm;
use App\Filament\Admin\Resources\KdpSelectPeriods\Tables\KdpSelectPeriodsTable;
use App\Models\KdpSelectPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KdpSelectPeriodResource extends Resource
{
    protected static ?string $model = KdpSelectPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Períodos KDP Select';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return KdpSelectPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KdpSelectPeriodsTable::configure($table);
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
            'index' => ListKdpSelectPeriods::route('/'),
            'create' => CreateKdpSelectPeriod::route('/create'),
            'edit' => EditKdpSelectPeriod::route('/{record}/edit'),
        ];
    }
}
