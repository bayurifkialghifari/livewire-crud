<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasMenu extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'menu_id', 'sub_menu_id'];
}
