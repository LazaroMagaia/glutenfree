<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
#[Fillable(['group', 'key', 'value'])]
class Settings extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

}
