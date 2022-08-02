<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';

    protected $fillable = [
        'nama',
    ];

    public function pajak()
    {
        return $this->belongsToMany(Pajak::class);
    }
}
