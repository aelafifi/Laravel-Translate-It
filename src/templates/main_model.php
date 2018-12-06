<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class StudlySingular extends Model
{
    use Translatable;

    public $translatedAttributes = [/*T*/];
    protected $fillable = [/*F*/];
}
