<?php

namespace Starterkit\Demo\Fields;

use Kirby\Form\NumberField;

class Currency extends NumberField
{

    public static function assets(): array
    {
        return [
            'fields/currency.js',
            'fields/currency.css'
        ];
    }

}
