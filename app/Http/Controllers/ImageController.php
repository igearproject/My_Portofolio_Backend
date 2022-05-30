<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

use Illuminate\Support\Facades\Validator;
use File;

class ImageController extends Controller
{
    public function getAll(Request $request){
        
        $data;
        $searchtext=$request->input('search');
        if($searchtext){
            $data=Component::where('name_file', 'LIKE','%'.$searchtext . '%')
                ->latest('created_at')->paginate(20);
        }else{
            $data=Image::latest('created_at')->paginate(20);
        }
        return response([
            "data"=>$data,
            "baseUrlImage"=>public_path('uploads')
        ],200);
    }
    
    public function uploadData(Request $request){
        $validator=Validator::make($request->all(),[
            'image'=>'bail|required|mimes:png,jpg,jpeg,gif|max:2048',
            'alt_text'=>'bail|nullable|string|max:255',
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter'
        ]);
        
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        
        if ($file = $request->file('image')) {
            
            $fileName = time().'_'.$request->file('image')->getClientOriginalName();  
            $request->file('image')->move(public_path('uploads'), $fileName);
            
            // storage/app/uploads/file.png
            // $request->file->storeAs('uploads', $fileName);
            
            // $request->file->storeAs('uploads', $fileName, 's3');
 
            $save = new Image();
            $save->name_file = $fileName;
            if($request->alt_text){
                $save->alt_text= $request->alt_text;
            }
            $save->save();
              
            return response([
                "message" => "File successfully uploaded",
                "file" => env('APP_URL').'uploads/'.$fileName
            ],200);
  
        }
        
    }
    
    public function getOne($id){
        $data=Image::where("id",$id)->get();
        if(!$data){
            return response([
                "message"=>'Image tidak ditemukan'
            ],404);
        }
        return response([
            "data"=>$data
        ],200);
    }
    
    public function edit(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'name_file'=>'bail|nullable|string|max:255||unique:images,name_file',
            'alt_text'=>'bail|nullable|string|max:255',
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'unique'=>':attribute :input sudah digunakan'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        $data=Image::find($id);
        if(!$data){
            return response([
                "message"=>'Image tidak ditemukan'
            ],404);
        }
        if($request->name_file){
            $data->name_file=$request->name_file;
        }
        if($request->alt_text){
            $data->alt_text=$request->alt_text;
        }
            
        $data->save();
        
        return response([
            "message"=>'Image berhasil diupdate',
            "data"=>$data
        ],200);
    }
    
    public function destroy(Request $request,$id){
        $data=Image::where("id",$id)->first();
        if(!$data){
            return response([
                "message"=>'Image tidak ditemukan'
            ],404);
        }
        File::delete(public_path('uploads/'.$data->name_file));
        $data->delete();
        
        return response([
            "message"=>'Image berhasil dihapus'
        ],200);
    }
}
