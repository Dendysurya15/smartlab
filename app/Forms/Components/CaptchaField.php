<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class CaptchaField extends Component
{
    protected string $view = 'forms.components.captcha-field';

    public static function make(): static
    {
        return app(static::class);
    }
}
