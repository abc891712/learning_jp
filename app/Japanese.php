<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Japanese extends Model
{
    protected $table = "japanese";

    protected $fillable = [
        'japanese','level','word','chinese',
    ];
}
