<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class userController extends Controller
{
    function index(Request $request)
    { 
        $data = DB::table('employees');
        if($request->has('sortby') && $request->sortby)
        {
            $data = $data->orderby('email', $request->sortby == 1 ? 'ASC' : "DESC")
            ->orderby('username', $request->sortby == 1 ? 'ASC' : "DESC");
        }
        $data = $data->paginate(10);
        return response()->json(['status' => true, "data" => $data], 200);
    }
    function store(Request $request){
        DB::table('employees')->insert($request->only('username','email' ,'phone','gender'));
        return response()->json(["status" => true, "message" => "Added Successfully"], 200);

    }
    function edit($id)
    {
        $data = DB::table('employees')->find($id);
        if($data)
        {
            return response()->json(['status' => true, "data" => $data], 200);
        }
        else{
            return response()->json(["status" => false, "message" => "Invalid Employee Id"], 200);
        }
    }
    function delete(Request $request)
    {
        $data = DB::table('employees')->find($request->id);
        if($data)
        {
            DB::table('employees')->delete($request->id);
            return response()->json(["status" => true, "message" => "Deleted Successfully"], 200);
        }
        else{
            return response()->json(["status" => true, "message" => "Invalid Employee Id"], 200);
        }
    }
    function update(Request $request)
    {
        DB::table('employees')->where('id', $request->id)
        ->update($request->only('username', 'email', 'phone','gender'));
        return response()->json(["status" => true, "message" => "Updated Successfully"], 200);

    }
}
