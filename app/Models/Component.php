<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Page;
use App\Models\listComponent;

class Component extends Model
{
    use HasFactory;
    protected $table='components';
    protected $fillable=[
        'name',
        'html',
        'data',
        'sample_image',
        'style',
        'script'
    ];
    
    public function page()
    {
        return $this->belongsToMany(Pages::class, 'list_components', 'component_id', 'page_id');
    }
    public function listComponents()
    {
        return $this->hasMany(listComponent::class,'id','component_id');
    }
}
