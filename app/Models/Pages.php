<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\Component;

class Pages extends Model
{
    use Uuid, HasFactory;
    protected $table='pages';
    protected $fillable=[
        'title',
        'meta_keyword',
        'meta_decryption',
        'publish',
        'publish_time',
        'type',
        'show_comment',
        'url'
    ];
    
    public function components()
    {
        return $this->belongsToMany(Component::class, 'list_components', 'page_id', 'component_id');
    }
}
