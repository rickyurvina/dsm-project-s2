<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function index(){
        return response()->json([
            'success' => true,
            'message' => 'Listado de usuarios',
            'data' => User::all()
        ], 200);
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            $data= $this->validate($request,[
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
            ]);
            User::create($data);
            DB::commit();

            return response()->json([
                'message'=>'Usuario creado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }

    public function update(Request $request, User $user){
        try{
            DB::beginTransaction();

            $data= $this->validate($request,[
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);
            if (!$user){
                return response()->json([
                    'message'=>'Usuario no encontrado',
                ],404);
            }
            $user->update($data);

            DB::commit();

            return response()->json([
                'message'=>'Usuario actualizado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }

    public function destroy(User $user){
        try{
            DB::beginTransaction();
            if (!$user){
                return response()->json([
                    'message'=>'Usuario no encontrado',
                ],404);
            }
            $user->delete();
            DB::commit();

            return response()->json([
                'message'=>'Usuario eliminado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }
}
