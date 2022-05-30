<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Pages;
use App\Models\listComponent;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function getAll(Request $request,Pages $pages){
        // if($request->input('publish')=='true'){
        //     $pages->where('publish',true)->whereNotIn('url',['home','login','register']);
        // }
        $data=$pages->latest('id')->paginate(20);
        // Inspector::latest('id')
        // ->select('id', 'firstname', 'status', 'state', 'phone')
        // ->where('firstname', 'LIKE', '%' . $searchtext . '%')
        // ->paginate(25);
        return response([
            "data"=>$data
        ],200);
    }
    public function getAllPublish(Request $request,Pages $pages){
        $dateNow=Carbon::now()->format('Y-m-d H:i:s');
        $data=$pages->where('publish',true)
            ->where('publish_time','<',$dateNow)
            ->whereNotIn('url',['home','login','register'])
            ->latest('id')
            ->paginate(20);
        return response([
            "data"=>$data
        ],200);
    }
    public function add(Request $request){
        $validator=Validator::make($request->all(),[
            'title'=>'bail|required|string|max:255',
            'type'=>'bail|required|in:home,page,post',
            'meta_keyword'=>'bail|nullable|string|max:255',
            'meta_decryption'=>'bail|nullable|string|max:255',
            'publish'=>'bail|required|boolean',
            // |date_format:Y-m-d H:i:s|after_or_equal:'.date(DATE_ATOM)
            'publish_time'=>'bail|nullable|date',
            'show_comment'=>'bail|boolean|required',
            'url'=>'bail|required|string|max:255|unique:pages,url',
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
            'unique'=>':attribute :input sudah digunakan',
            'min'=>':attribute minimal :min karakter',
            'same'=>':attribute tidak sama'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        
        $data=Pages::create([
            'title'=>$request->title,
            'type'=>$request->type,
            'meta_keyword'=>$request->meta_keyword,
            'meta_decryption'=>$request->meta_decryption,
            'publish'=>$request->publish,
            'publish_time'=>$request->publish_time,
            'show_comment'=>$request->show_comment,
            'url'=>$request->url
        ]);
        
        return response([
            "message"=>"Page baru berhasil dibuat",
            "data"=>$data
        ],200);
    }
    
    public function show($id){
        $data=Pages::find($id);
        if(!$data){
            return response([
                "message"=>'Page tidak ditemukan'
            ],404);
        }
        // $components=Pages::find($id)->components()->get();
        $components=listComponent::with('components')
            ->where('page_id',$id)
            ->orderBy('order_number','ASC')
            // ->components()
            ->get();
        return response([
            "data"=>$data,
            "components"=>$components
        ],200);
    }
    
    public function showPage($url){
        $dateNow=Carbon::now()->format('Y-m-d H:i:s');
        $data=Pages::where('url',$url)
            ->where('publish',true)
            ->where('publish_time','<',$dateNow)
            ->first();
        if(!$data){
            return response([
                "message"=>'Page tidak ditemukan'
            ],404);
        }
        $components=listComponent::with('components')
            ->where('page_id',$data->id)
            ->orderBy('order_number','ASC')
            ->get();
        return response([
            "data"=>$data,
            "components"=>$components
        ],200);
    }
    
    public function edit(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'title'=>'bail|nullable|string|max:255',
            'type'=>'bail|nullable|in:home,page,post',
            'meta_keyword'=>'bail|nullable|string|max:255',
            'meta_decryption'=>'bail|nullable|string|max:255',
            'publish'=>'bail|nullable|boolean',
            'publish_time'=>'bail|nullable|date_format:Y-m-d H:i:s',
            'show_comment'=>'bail|nullable|boolean',
            'url'=>'bail|string|nullable|max:255',
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'email'=>'email tidak valid',
            'unique'=>':attribute :input sudah digunakan',
            'min'=>':attribute minimal :min karakter',
            'same'=>':attribute tidak sama'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        $data=Pages::find($id);
        if(!$data){
            return response([
                "message"=>'Page tidak ditemukan'
            ],404);
        }
        if($request->title){
            $data->title=$request->title;
            $data->type=$request->type;
            $data->meta_keyword=$request->meta_keyword;
            $data->meta_decryption=$request->meta_decryption;
            $data->publish=$request->publish;
            $data->publish_time=$request->publish_time;
            $data->show_comment=$request->show_comment;
            $data->url=$request->url;
        }else{
            $data->publish=$request->publish;
            $data->publish_time=$request->publish_time;
        }
        
        $data->save();
        
        return response([
            "message"=>'Page berhasil diupdate',
            "data"=>$data
        ],200);
    }
    
    public function destroy($id){
        
        $data=Pages::find($id);
        if(!$data){
            return response([
                "message"=>'Page tidak ditemukan'
            ],404);
        }
        
        $data->delete();
        
        return response([
            "message"=>'Page berhasil dihapus'
        ],200);
    }
    
    
}
