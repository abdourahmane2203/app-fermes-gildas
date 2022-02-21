<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;


class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $post = Post::paginate(20);
        if (count($post)==0) {
            $post=[];
            $message = 'Aucune poste trouvée dans la base';
        }else {
            $message = "Tous les postes";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "post"=> $post,    
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
        $post = new Post;
        $data = $request->only('category','title', 'image', 'description', 'type', 'like', 'user_id');
        $validator=Validator::make($data, [ 
            'category'=>'required|string',
            'title' => 'required|string',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'type' => 'required|string',
            'like'=>'required|integer',
            'user_id' => 'required|integer',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Validation de données / add region attribute   
        extract($data);
        $post->category = $category;   
        $post->title = $title;
        $post->image = $image;
        $post->description = $description;
        $post->type = $type;
        $post->like = $like;
        $post->user_id = $user_id; 
        try {
            $post->save();
        } catch (\Throwable $th) { 
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette poste existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de post reussie",         
                    "post"=> $post,    
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
        $post = Post::where('id', $id)->get();
        
        if (!$post) {
            $post=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "post"=> $post,
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
        $post = Post::find($id);
        if (!$post) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette poste n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('category','title', 'image', 'description', 'type', 'like', 'user_id');
        $post->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du post reussi',       
                'post'=> $post,
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
        $post = Post::find($id);
        if (!$post) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette post n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$post->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'post'=> $post
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'post'=> $post
              ]   
        ], 404);
    }
}
