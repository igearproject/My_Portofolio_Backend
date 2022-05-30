<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Component;
use App\Models\Page;

class listComponent extends Model
{
    use HasFactory;
    protected $table='list_components';
    protected $fillable=[
        'order_number',
        'page_id',
        'component_id'
    ];
    public function page()
    {
        return $this->belongsTo(Pages::class,'page_id','id');
    }
    public function components()
    {
        return $this->belongsTo(Component::class,'component_id','id');
    }
}
