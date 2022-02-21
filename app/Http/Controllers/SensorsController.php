<?php

namespace App\Http\Controllers;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sensor = Sensor::paginate(20);
        if (count($sensor)==0) {
            $sensor=[];
            $message = 'Aucune capteur trouvée dans la base';
        }else {
            $message = "Tous les capteurs";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "sensor"=> $sensor,    
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
        $sensor = new Sensor;
        $data = $request->only('name', 'description', 'type', 'farm_id', 'animal_id');
        $validator=Validator::make($data, [
            'name'=>'required|string',
            'description' => 'required|string',
            'type' => 'required|string',
            'farm_id' => 'required|integer',
            'animal_id'=>'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // Validation de données / add region attribute   
        extract($data);
        $sensor->name = $name;   
        $sensor->description = $description;  
        $sensor->type =$type;  
        $sensor->farm_id = $farm_id; 
        $sensor->animal_id = $animal_id;  
        try {
            $sensor->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette ferme capteur déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }       
         return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de capteur reussie",         
                    "sensor"=> $sensor,    
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
        $sensor = Sensor::where('id', $id)->get();
        
        if (!$sensor) {
            $sensor=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "sensor"=> $sensor,
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
        $sensor = Sensor::find($id);
        if (!$sensor) {
            
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette capteur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'description', 'type', 'farm_id', 'animal_id');
        $sensor->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du capteur reussi',       
                'sensor'=> $sensor,
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
        $sensor = Sensor::find($id);
        if (!$sensor) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette capteur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$sensor->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'sensor'=> $sensor
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'sensor'=> $sensor
              ]   
        ], 404);
    }
}
