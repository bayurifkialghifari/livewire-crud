<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreadJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'bread_id', 'origin_table', 'origin_key',
        'foreign_table', 'foreign_key', 'join_type',
    ];

    public function bread()
    {
        return $this->belongsTo('App\Models\Bread', 'bread_id');
    }
}
