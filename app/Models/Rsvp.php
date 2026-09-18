<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'name',
        'attending',
        'guests',
        'phone',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'attending' => 'boolean',
        ];
    }
}
