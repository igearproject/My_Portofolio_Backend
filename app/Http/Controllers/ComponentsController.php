<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Component;
use App\Models\listComponent;

class ComponentsController extends Controller
{
    public function getAll(Request $request){
        $data;
        $searchtext=$request->input('search');
        if($searchtext){
            $data=Component::where('name', 'LIKE','%'.$searchtext . '%')
                ->latest('id')->get();
                // ->paginate(20);
        }else{
            $data=Component::latest('id')->get();
        }
        return response([
            "data"=>$data
        ],200);
    }
    public function add(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'bail|required|string|max:255',
            // |unique:components,name
            'html'=>'bail|nullable|string',
            'data'=>'bail|nullable|string',
            'style'=>'bail|nullable|string',
            'script'=>'bail|nullable|string',
            'sample_image'=>'bail|nullable|string'
            
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
        $component=Component::where(["name"=>$request->name])->first();
        if($component){
            $data=$component;
        }else{
            $data=Component::create([
                'name'=>$request->name,
                'html'=>$request->html,
                'data'=>$request->data,
                'style'=>$request->style,
                'script'=>$request->script,
                'sample_image'=>$request->sample_image
            ]);
        }
        
        if($request->input('page')){
            $order_number=$request->order_number;
            if(!$order_number){
                $component_length=listComponent::where('page_id',$request->input('page'))->count();
                $order_number=$component_length+1;
            }
            $list=listComponent::create([
                "order_number"=>$order_number,
                "page_id"=>$request->input('page'),
                "component_id"=>$data->id
            ]);
            
            return response([
                "message"=>"Component baru berhasil dibuat",
                "data"=>$data,
                "listComponent"=>$list
            ],200);
        }
        
        return response([
            "message"=>"Component baru berhasil dibuat",
            "data"=>$data
        ],200);
    }
    
    public function show($id){
        $data=Component::find($id);
        if(!$data){
            return response([
                "message"=>'Component tidak ditemukan'
            ],404);
        }
        return response([
            "data"=>$data
        ],200);
    }
    
    public function edit(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'name'=>'bail|nullable|string|max:255',
            // |unique:components,name
            'html'=>'bail|nullable|string',
            'data'=>'bail|nullable|string',
            'style'=>'bail|nullable|string',
            'script'=>'bail|nullable|string',
            'sample_image'=>'bail|nullable|string'
            
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
        $data=Component::find($id);
        if(!$data){
            return response([
                "message"=>'Component tidak ditemukan'
            ],404);
        }
        $data->name=$request->name;
        $data->html=$request->html;
        $data->data=$request->data;
        $data->style=$request->style;
        $data->script=$request->script;
        $data->sample_image=$request->sample_image;
            
        $data->save();
        
        return response([
            "message"=>'Component berhasil diupdate',
            "data"=>$data
        ],200);
    }
    
    public function destroy(Request $request,$id){
        $list=ListComponent::where("component_id",$id);
        if($request->input('page')){
            $list->where("page_id",$request->input('page'));
        }
        $list->delete();
        
        if($request->input('all')){
            $data=Component::find($id);
            if(!$data){
                return response([
                    "message"=>'Component tidak ditemukan'
                ],404);
            }
            
            $data->delete();
        }
        
        return response([
            "message"=>'Component berhasil dihapus'
        ],200);
    }
}
