<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'name', 'url', 'class', 'icon', 'index'];

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu', 'menu_id');
    }
}
