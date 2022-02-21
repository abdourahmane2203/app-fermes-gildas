<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Fonction;
use Illuminate\Support\Facades\Validator;


class FonctionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fonction = Fonction::paginate(20);
        if (count($fonction)==0) {
            $fonction=[];
            $message = 'Aucune fonction trouvée dans la base';
        }else {
            $message = "Tous les fonctions";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "fonction"=> $fonction,    
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
        $fonction = new Fonction;
        $data = $request->only('name', 'farm_id');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'farm_id' => 'required|integer',
            
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

       
        // Validation de données / add region attribute   
        extract($data);
        $fonction->name =$name;   
        $fonction->farm_id = $farm_id;
        try {
            $fonction->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette fonction existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }
        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de fonction reussie",        
                    "fonction"=> $fonction,    
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
        $fonction = Fonction::where('id', $id)->get();
        
        if (!$fonction) {
            $fonction=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "fonction"=> $fonction,
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
        $fonction = Fonction::find($id);
        if (!$fonction) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette fonction n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'farm_id');
        $fonction->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du fonction reussi',       
                'fonction'=> $fonction,
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
        $fonction = Fonction::find($id);
        if (!$fonction) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette fonction n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$fonction->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'fonction'=> $fonction
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'fonction'=> $fonction
              ]   
        ], 404);
    }
}
