<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\listComponent as ListComponent;

class ListComponentsController extends Controller
{
    public function getAll(Request $request){
        $data=ListComponent::latest('id')->paginate(20);
        return response([
            "data"=>$data
        ],200);
    }
    public function add(Request $request){
        $validator=Validator::make($request->all(),[
            'order_number'=>'bail|nullable|integer',
            'page_id'=>'bail|required|string',
            'component_id'=>'required|integer'
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'unique'=>':attribute :input sudah digunakan',
            'integer'=>':attribute harus bernilai integer',
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        $order_number=$request->order_number;
        if(!$order_number){
            $component_length=ListComponent::where('page_id',$request->page_id)->count();
            $order_number=$component_length+1;
        }
        $data=ListComponent::create([
            'order_number'=>$order_number,
            'page_id'=>$request->page_id,
            'component_id'=>$request->component_id
        ]);
        
        return response([
            "message"=>"List component baru berhasil dibuat",
            "data"=>$data
        ],200);
    }
    
    public function show($id){
        $data=ListComponent::find($id);
        if(!$data){
            return response([
                "message"=>'List component tidak ditemukan'
            ],404);
        }
        return response([
            "data"=>$data
        ],200);
    }
    
    public function edit(Request $request,$id){
        $validator=Validator::make($request->all(),[
            'order_number'=>'bail|nullable|integer',
            'page_id'=>'bail|nullable|string',
            'component_id'=>'nullable|integer'
            
        ],[
            'required'=>':attribute belum diinputkan',
            'max'=>':attribute tidak boleh lebih dari :max karakter',
            'unique'=>':attribute :input sudah digunakan',
            'integer'=>':attribute harus bernilai integer'
        ]);
        if($validator->fails()){
            return response([
                "message"=>$validator->errors()->first()
            ],400);
        }
        $data=ListComponent::find($id);
        if(!$data){
            return response([
                "message"=>'List component tidak ditemukan'
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
            "message"=>'List component berhasil diupdate',
            "data"=>$data
        ],404);
    }
    
    // listComponent change order number
    public function changeOrderNumber(Request $req, $id){
        
        $list=ListComponent::where('id',$id)->first();
        
        if(!$list){
            return response([
                "message"=>'List Component tidak ditemukan'
            ],404);
        }
        
        if($req->input('up')=='true'){
            $list->order_number=$list->order_number-1;
        }else{
            
            $list->order_number=$list->order_number+1;
        }
        $list->save();
        
        return response([
            "message"=>'Urutan List Component berhasil dirubah',
            "data"=>$list
        ],200);
    }
    
    public function destroy($id){
        
        $data=ListComponent::find($id);
        if(!$data){
            return response([
                "message"=>'List component tidak ditemukan'
            ],404);
        }
        
        $data->delete();
        
        return response([
            "message"=>'List component berhasil dihapus'
        ],200);
    }
}
