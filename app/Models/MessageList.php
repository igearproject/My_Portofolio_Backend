<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailList;

class MessageList extends Model
{
    use HasFactory;
    protected $table='message_list';
    protected $fillable=[
        'email_from_id',
        'email_to',
        'subject',
        'message',
    ];
    
    public function emailList()
    {
        return $this->belongsTo(EmailList::class, 'email_from_id', 'id');
    }
}
