<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCheck
{
    
    public function handle(Request $request, Closure $next)
    {
        $id=$request->user()->id;
        $role=DB::table('roles')->where('user_id', $id)->first();
        if($role->name=="admin"){
            return $next($request);
        }
        return response([
            "status"=>"error",
            "message"=>"Kamu tidak memiliki akses",
        ],403);
        
    }
}
