<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
           'success' => true,
            'message' => 'Listado de posts',
            'data' => Post::with(['comments','user'])->get()
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try{
            DB::beginTransaction();
            $data= $this->validate($request,[
                'code' => 'required',
                'title' => 'required',
                'description' => 'string',
                'user_id' => 'required',
                'image_url' => 'string'
            ]);
            Post::create($data);
            DB::commit();

            return response()->json([
                'message'=>'Post creado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        try{
            DB::beginTransaction();

            $data= $this->validate($request,[
                'code' => 'required',
                'title' => 'required',
                'description' => 'string',
                'user_id' => 'required',
                'image_url' => 'string'
            ]);
            if (!$post){
                return response()->json([
                    'message'=>'Post no encontrado',
                ],404);
            }
            $post->update($data);

            DB::commit();

            return response()->json([
                'message'=>'Post actualizado correctamente',
                'success'=>True
            ]);
        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        try{
            DB::beginTransaction();
            if (!$post){
                return response()->json([
                    'message'=>'Post no encontrado',
                ],404);
            }
            $post->delete();
            DB::commit();

            return response()->json([
                'message'=>'Post eliminado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }
}
