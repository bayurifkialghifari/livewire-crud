<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreadFieldRule extends Model
{
    use HasFactory;

    protected $fillable = ['bread_field_id', 'rule', 'message'];

    public function field()
    {
        return $this->belongsTo('App\Models\BreadField', 'bread_id');
    }
}
