<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'class', 'icon'];

    public function sub_menus()
    {
        return $this->hasMany('App\Models\SubMenu', 'menu_id');
    }
}
