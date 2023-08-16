<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EscolaResource\Pages;
use App\Filament\Resources\EscolaResource\RelationManagers;
use App\Filament\Resources\EscolaResource\RelationManagers\ModalidadesRelationManager;
use App\Models\Escola;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EscolaResource extends Resource
{
    protected static ?string $model = Escola::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados Gerais')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Select::make('user_id')
                            ->label('Responsável')
                            ->relationship('user', 'name')
                            ->required(),
                        TextInput::make('nome')
                            ->label('Nome Academia')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('cnpj')
                            ->label('CNPJ')
                            ->mask('99.999.999/9999-99')
                            ->placeholder('99.999.999/9999-99')
                            ->required()
                            ->maxLength(18),
                    ]),
                Section::make('Endereço')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('uf')
                            ->label('UF')
                            ->required()
                            ->maxLength(2),
                        TextInput::make('cidade')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('endereco_em_breve')
                            ->columnSpan(2),
                        TextInput::make('cep')
                            ->maxLength(255),
                        TextInput::make('endereco')
                            ->label('Endereço')
                            ->maxLength(255),
                        TextInput::make('numero_endereco')
                            ->label('Número Endereço')
                            ->maxLength(255),
                        TextInput::make('complemento')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                
                Section::make('Contatos')
                    ->icon('heroicon-o-at-symbol')
                    ->schema([
                        TextInput::make('instagram')
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-globe-alt'),
                        TextInput::make('celular')
                            ->mask('(99) 9 9999-9999')
                            ->placeholder('(99) 9 9999-9999')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-device-phone-mobile'),
                    ]),
                Section::make('Logo')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('')
                            ->required()
                            ->image()
                            ->acceptedFileTypes(['image/webp'])
                            ->rules(['dimensions:width=500,height=500'])
                            ->directory('assets_external/img/escolas')
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file): string => (string) date('YmdHis') . '.webp'
                            ),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Escola')
                    ->searchable(),
                TextColumn::make('cnpj')
                    ->label('CNPJ')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nome')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.last_name')
                    ->label('Sobrenome')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('uf')
                    ->searchable(),
                TextColumn::make('cidade')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListEscolas::route('/'),
            'create' => Pages\CreateEscola::route('/create'),
            'edit' => Pages\EditEscola::route('/{record}/edit'),
        ];
    }
}
