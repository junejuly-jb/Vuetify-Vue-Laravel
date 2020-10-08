<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    public function getAllUsers(){
        return User::all();
    }

    public function delete($id){    
        $user = User::find($id);
        $user->delete();

        return response()->json([
            'message' => 'Item removed'
        ]);
    }

    public function addUser(Request $request){
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();
        return response()->json([
            'message' => 'User added!',
            'data' => $user
        ]);
    }
    public function editUser(Request $request, $id){
        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        return response()->json([
            'message' => 'User edited!'
        ]);
    }
}
