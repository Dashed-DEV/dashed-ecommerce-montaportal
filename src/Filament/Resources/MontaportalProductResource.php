<?php

namespace Qubiqx\QcommerceEcommerceMontaportal\Filament\Resources;

use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\BooleanColumn;
use Qubiqx\QcommerceEcommerceMontaportal\Models\MontaportalProduct;
use Qubiqx\QcommerceEcommerceMontaportal\Filament\Resources\MontaportalProductResource\Pages\EditMontaportalProduct;
use Qubiqx\QcommerceEcommerceMontaportal\Filament\Resources\MontaportalProductResource\Pages\ListMontaportalProducts;

class MontaportalProductResource extends Resource
{
    protected static ?string $model = MontaportalProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'E-commerce';
    protected static ?string $navigationLabel = 'Montaportal producten';
    protected static ?string $label = 'Montaportal product';
    protected static ?string $pluralLabel = 'Montaportal producten';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Placeholder::make('')
                        ->content(fn ($record) => 'Bewerk instellingen voor Montaportal voor product ' . $record->product->name),
                        Toggle::make('sync_stock')
                            ->label('Sync voorraad'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('sync_stock')
                    ->label('Sync voorraad'),

            ])
            ->filters([
                //
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
            'index' => ListMontaportalProducts::route('/'),
            'edit' => EditMontaportalProduct::route('/{record}/edit'),
        ];
    }
}
