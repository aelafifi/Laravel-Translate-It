<?php

namespace ElMag\TranslateIt;

use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
    protected $fillable = [
        'code',
        'name',
        'direction',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function setDefault()
    {
        static::where('id', '!=', $this->id)->update(['is_default' => 0]);
        $this->is_default = true;
        $this->save();
    }
}