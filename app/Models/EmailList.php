<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use App\Models\MessageList;

class EmailList extends Model
{
    use Uuid, HasFactory;
    protected $table='email_list';
    protected $fillable=[
        'name',
        'email',
        'token',
        'verified'
    ];
    
    public function messageList()
    {
        return $this->hasMany(MessageList::class, 'id', 'email_from_id');
    }
    
}
