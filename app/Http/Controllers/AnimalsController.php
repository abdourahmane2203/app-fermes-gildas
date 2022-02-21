<?php

namespace App\Http\Controllers;
use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $animal = Animal::paginate(20);
        if (count($animal)==0) {
            $animal=[];
            $message = 'Aucune animal trouvée dans la base';
        }else {
            $message = "Tous les animals";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "animal"=> $animal,    
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
        $animal = new Animal; 

        $data = $request->only( 'name', 'age', 'color', 'farm_id', 'desease_id');
        $validator=Validator::make($data, [
            'name'=>'required|string',
            'age' => 'required|integer',
            'color' => 'required|string',
            'farm_id' => 'required|integer',
            'desease_id'=>'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        extract($data);
        $animal->name = $name;   
        $animal->age = $age;  
        $animal->color =$color;  
        $animal->farm_id = $farm_id;
        $animal->desease_id = $desease_id;
        try {
            $animal->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet animal existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de animal reussie",         
                    "animal"=> $animal,    
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
        $animal = Animal::where('id', $id)->get();
        
        if (!$animal) {
            $animal=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "animal"=> $animal,
           ]       
       ], 200);
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
        $animal = Animal::find($id);
        // animal doesn't exist
        if (!$animal) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette animal n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        // animal found
        $data = $request->only( 'name', 'age', 'color', 'farm_id', 'desease_id');
        $animal->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du animal reussi',       
                'animal'=> $animal,
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
        $animal = Animal::find($id);
        if (!$animal) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette animal n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$animal->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'animal'=> $animal
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'animal'=> $animal
              ]   
        ], 404);
    }
}
