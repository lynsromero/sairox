<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option_name', 'option_value', 'autoload'];

    protected function casts(): array
    {
        return [
            'option_value' => 'array',
        ];
    }
}
