<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasMenu extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'menu_id', 'sub_menu_id'];

    public function menu()
    {
        return $this->belongsTo('App\Models\Menu', 'menu_id');
    }

    public function sub_menu()
    {
        return $this->belongsTo('App\Models\SubMenu', 'sub_menu_id');
    }

    public function role()
    {
        return $this->belongsTo('Spatie\Permission\Models\Role', 'role_id');
    }
}
