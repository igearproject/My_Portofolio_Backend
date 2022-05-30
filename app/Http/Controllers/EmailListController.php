<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\EmailList;
use App\Models\MessageList;
use Illuminate\Support\Str;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

use Mail;
use App\Mail\MailToMe;

class EmailListController extends Controller
{
    public function getAll(){
        $data=EmailList::latest('id')->paginate(20);
        
        return response([
            "data"=>$data
        ],200);
    }
    
    public function getOne($id){
        $data=EmailList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Email tidak ditemukan"
            ],404);
        }
        return response([
            "data"=>$data
        ],200);
    }
    
    public function add(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'bail|required|string|max:255',
            'email'=>'bail|required|email|max:255|unique:email_list,email',
            // 'token'=>'bail|nullable|string|max:255',
            // 'verified'=>'nullable|boolean'
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
        $randomKey=Str::random(40);
        
        $data=EmailList::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'token'=>$randomKey,
            'verified'=>false
        ]);
        
        return response([
            "message"=>"Email baru berhasil ditambahkan",
            "data"=>$data
        ],200);
    }
    
    public function edit(Request $request, $id){
        $validator=Validator::make($request->all(),[
            'name'=>'bail|required|string|max:255',
            'email'=>'bail|required|email|max:255',
            'token'=>'bail|nullable|string|max:255',
            'verified'=>'nullable|boolean'
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
        
        $data=EmailList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Email tidak ditemukan"
            ],404);
        }
        $data->name=$request->name;
        if($data->email!=$request->email){
            $data->email=$request->email;
        }
        if($request->token){
            $data->token=$request->token;
        }
        if($request->verified){
            $data->verified=$request->verified;
        }
        
        $data->save();
        
        return response([
            "message"=>"Email berhasil diedit",
            "data"=>$data
        ],200);
        
    }
    
    public function verification(Request $request,$id, $encrypToken, $messageId){
        
        $data=EmailList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Email tidak ditemukan"
            ],404);
        }
        $token='';
        try{
            $token=Crypt::decryptString($encrypToken);
        }catch(DecryptException $e){
            return response([
                "message"=>"Token Tidak dapat Digunakan =>".$e
            ],400);
        }
        $randomKey=Str::random(40);
        if($data->token==$token){
            if($request->input('name')){
                $data->name=$request->input('name');
            }
            $data->token=$randomKey;
            $data->verified=true;
            
            $data->save();
            
            $message=MessageList::where('id',$messageId)->where('email_from_id',$data->id)->first();
            if($message){
                
                $mailData=[
                    "id"=>$data->id,
                    "name"=>$data->name,
                    "email"=>$data->email,
                    "status"=>$data->verified,
                    "message"=>$message->message,
                    "subject"=>$message->subject,
                ];
                
                Mail::to($message->email_to)
                    ->send(new MailToMe($mailData));
            }
            
            return response([
                "message"=>"Verifikasi email berhasil",
            ],200);
        }
        
        // $data->token=$randomKey;
        $data->verified=false;
        
        $data->save();
        
        return response([
            "message"=>"Link verifikasi salah",
        ],400);
        
        
    }
    
    public function destroy($id){
        MessageList::where('email_from_id',$id)->delete();
        $data=EmailList::where('id',$id)->first();
        if(!$data){
            return response([
                "message"=>"Email tidak ditemukan"
            ],404);
        }
        $data->delete();
        
        return response([
            "message"=>"Email berhasil dihapus"
        ],200);
    }
    
}
