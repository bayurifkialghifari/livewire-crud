<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreadField extends Model
{
    use HasFactory;

    protected $fillable = [
        'bread_id', 'foreign_table', 'foreign_key',
        'foreign_field', 'field', 'type', 'display_name',
        'placeholder', 'class_alt', 'default_value', 'id_alt',
        'is_required', 'is_searchable', 'is_browse', 'is_readonly',
        'is_edit', 'is_add', 'source', 'source_id', 'source_value',
        'file_accept', 'description', 'description_class', 'order',
    ];

    public function bread()
    {
        return $this->belongsTo('App\Models\Bread', 'bread_id');
    }

    public function rules()
    {
        return $this->hasMany('App\Models\BreadFieldRule', 'bread_field_id');
    }
}
