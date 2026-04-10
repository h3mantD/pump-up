<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\StockStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;

final class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->rows(4),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(collect(StockStatus::cases())->mapWithKeys(
                                fn (StockStatus $status): array => [$status->value => ucfirst(str_replace('_', ' ', $status->value))]
                            )),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->required()
                            ->options(Category::pluck('name', 'id')),
                    ]),
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->directory('products')
                        ->disk('public')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048),
                ]),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(StockStatus::cases())->mapWithKeys(
                        fn (StockStatus $status): array => [$status->value => ucfirst(str_replace('_', ' ', $status->value))]
                    )),
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query) => $query->where('stock', '<', 10))
                    ->label('Low Stock (< 10)'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
