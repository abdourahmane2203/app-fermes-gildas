<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $category = Category::paginate(20);
        if (count($category)==0) {
            $category=[];
            $message = 'Aucune categorie trouvée dans la base';
        }else {
            $message = "Tous les categories";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "category"=> $category,    
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
        $category = new Category; 
        $data = $request->only('name');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

       
        // Validation de données / add region attribute  
        extract($data); 
        $category->name = $name;   
        try {
            $category->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette categorie existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de categorie reussie",         
                    "categorie"=> $category,    
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
        $category = Category::where('id', $id)->get();
        
        if (!$category) {
            $category=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "category"=> $category,
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
        $category = Category::find($id);
        // category doesn't exist
        if (!$category) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette categorie n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        // category found
        $data = $request->only('name');
        $category->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du categorie reussi',       
                'category'=> $category,
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
        $category = Category::find($id);
        if (!$category) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette categorie n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$category->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'category'=> $category
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'category'=> $category
              ]   
        ], 404);
    }
}
