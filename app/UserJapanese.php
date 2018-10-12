<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserJapanese extends Model
{
    protected $table = "user_japanese";

    protected $fillable = ["user_id","japanese_id"];
}
