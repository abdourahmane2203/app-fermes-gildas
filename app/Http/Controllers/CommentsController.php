<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comment = Comment::paginate(20);
        if (count($comment)==0) {
            $comment=[];
            $message = 'Aucun commentaire trouvée dans la base';
        }else {
            $message = "Tous les commentaires";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "comment"=> $comment,    
            ]   
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $comment = new Comment; 
        $data = $request->only( 'content', 'user_id','post_id');
        $validator=Validator::make($data, [
            'content'=>'required|string',
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Validation de données / add region attribute 
        extract($data);
        $comment->content = $content;  
        $comment->user_id = $user_id;   
        $comment->post_id = $post_id;
        try {
            $comment->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet commentaire existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de comment reussie",         
                    "comment"=> $comment,    
               ]   
           ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $id = intval($id);
        $comment = Comment::where('id', $id)->get();
        
        if (!$comment) {
            $comment=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "comment"=> $comment,
           ]       
       ], 201);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $comment = Comment::find($id);
        if (!$comment) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet commentaire n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only( 'content', 'user_id','post_id');
        $comment->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du commentaire reussi',       
                'comment'=> $comment,
             ]
        ], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $comment = Comment::find($id);
        if (!$comment) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette commentaire n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$comment->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'comment'=> $comment
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'comment'=> $comment
              ]   
        ], 404);
    }
}
