<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'success' => true,
            'message' => 'Listado de comentarios',
            'data' => Comment::with('post')->get()
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
                'comment' => 'required',
                'post_id' => 'required',
            ]);
            Comment::create($data);
            DB::commit();

            return response()->json([
                'message'=>'Comentario creado correctamente',
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
    public function update(Request $request, Comment $comment)
    {
        //
        try{
            DB::beginTransaction();

            $data= $this->validate($request,[
                'comment' => 'required',
            ]);
            if (!$comment){
                return response()->json([
                    'message'=>'Comentario no encontrado',
                ],404);
            }
            $comment->update($data);

            DB::commit();

            return response()->json([
                'message'=>'Comentario actualizado correctamente',
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
    public function destroy(Comment $comment)
    {
        //
        try{
            DB::beginTransaction();
            if (!$comment){
                return response()->json([
                    'message'=>'Comentario no encontrado',
                ],404);
            }
            $comment->delete();
            DB::commit();
            return response()->json([
                'message'=>'Comentario eliminado correctamente',
                'success'=>True
            ]);

        }catch (\Throwable $e){
            DB::rollback();
            throw  new \Exception($e->getMessage());
        }
    }
}
