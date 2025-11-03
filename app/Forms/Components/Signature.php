<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class Signature extends Component
{
    protected string $view = 'forms.components.signature';

    public static function make(): static
    {
        return app(static::class);
    }
}
