<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\MessageList;
use App\Models\EmailList;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

use Mail;
use App\Mail\EmailVerification;



class MessageListController extends Controller
{
    public function getAll(){
        $data=MessageList::with('emailList')->latest('created_at')->paginate(20);
        
        return response([
            "data"=>$data
        ],200);
    }
    
    public function getOne($id){
        $data=MessageList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Pesan tidak ditemukan"
            ],404);
        }
        return response([
            "data"=>$data
        ],200);
    }
    
    public function add(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'bail|required|email:filter|max:255',
            'name'=>'bail|required|string|max:255',
            'email_to'=>'bail|required|string|max:255',
            'subject'=>'bail|required|string|max:255',
            'message'=>'required|string|max:1000',
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        
        $randomKey=Str::random(40);
        $email=EmailList::firstOrCreate(
            ['email'=>$request->email],
            [
                'name'=>$request->name,
                // 'email'=>$request->email,
                'token'=>$randomKey,
                'verified'=>false
            ]
        );
        
        $data=MessageList::create([
            'email_from_id'=>$email->id,
            'email_to'=>$request->email_to,
            'subject'=>$request->subject,
            'message'=>$request->message,
        ]);
        
        if($email&&$data){
            $mailData=[
                "id"=>$email->id,
                "token"=>Crypt::encryptString($email->token),
                "name"=>$request->name,
                'message'=>$data->message,
                'subject'=>$data->subject,
                'messageId'=>$data->id,
            ];
            
            Mail::to($email->email)
                // ->subject($data->subject)
                ->send(new EmailVerification($mailData));
        }
        
        return response([
            "message"=>"Pesan baru berhasil ditambahkan",
            "data"=>$data
        ],200);
    }
    
    public function edit(Request $request, $id){
        $validator=Validator::make($request->all(),[
            'email_from_id'=>'bail|nullable|string|max:255',
            'email_to'=>'bail|nullable|string|max:255',
            'subject'=>'bail|nullable|string|max:255',
            'message'=>'nullable|string|max:1000',
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
            'unique'=>':attribute :input sudah digunakan',
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        
        $data=MessageList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Pesan tidak ditemukan"
            ],404);
        }
        
        if($request->email_from_id){
            $email=EmailList::where('id',$id)->first();
            if(!$email){
                return response([
                    "message"=>"Pesan tidak ditemukan"
                ],404);
            }
            $data->email_from_id=$request->email_from_id;
        }
        if($request->email_to){
            $data->email_to=$request->email_to;
        }
        if($request->subject){
            $data->subject=$request->subject;
        }
        if($request->message){
            $data->message=$request->message;
        }
        $data->save();
        
        return response([
            "message"=>"Pesan berhasil diedit",
            "data"=>$data
        ],200);
        
    }
    
    public function destroy($id){
        $data=MessageList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Pesan tidak ditemukan"
            ],404);
        }
        $data->delete();
        
        return response([
            "message"=>"Pesan berhasil dihapus"
        ],200);
    }
}
