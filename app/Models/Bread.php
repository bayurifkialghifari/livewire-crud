<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bread extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'url_slug', 'display_name_singular',
        'display_name_plural', 'icon', 'table_name',
        'primary_key', 'order_by', 'order', 'is_join',
        'custom_button', 'active_menu', 'description',
    ];

    public static function booted()
    {
        self::saving(function($model) {
            $model->url_slug = Str::slug($model->name);
        });
    }

    public function bread_join()
    {
        return $this->hasMany('App\Models\BreadJoin', 'bread_id');
    }

    public function bread_field()
    {
        return $this->hasMany('App\Models\BreadField', 'bread_id');
    }
}
