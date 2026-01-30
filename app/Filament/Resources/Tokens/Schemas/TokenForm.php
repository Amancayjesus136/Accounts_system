<?php

namespace App\Filament\Resources\Tokens\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TokenForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_user')
                    ->required()
                    ->numeric(),
                TextInput::make('number_token')
                    ->required()
                    ->numeric(),
            ]);
    }
}
