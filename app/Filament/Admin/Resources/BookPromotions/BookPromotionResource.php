<?php

namespace App\Filament\Admin\Resources\BookPromotions;

use App\Filament\Admin\Resources\BookPromotions\Pages\CreateBookPromotion;
use App\Filament\Admin\Resources\BookPromotions\Pages\EditBookPromotion;
use App\Filament\Admin\Resources\BookPromotions\Pages\ListBookPromotions;
use App\Filament\Admin\Resources\BookPromotions\Schemas\BookPromotionForm;
use App\Filament\Admin\Resources\BookPromotions\Tables\BookPromotionsTable;
use App\Models\BookPromotion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookPromotionResource extends Resource
{
    protected static ?string $model = BookPromotion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Promociones';

    protected static ?string $recordTitleAttribute = 'promotion_name';

    public static function form(Schema $schema): Schema
    {
        return BookPromotionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookPromotionsTable::configure($table);
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
            'index' => ListBookPromotions::route('/'),
            'create' => CreateBookPromotion::route('/create'),
            'edit' => EditBookPromotion::route('/{record}/edit'),
        ];
    }
}
