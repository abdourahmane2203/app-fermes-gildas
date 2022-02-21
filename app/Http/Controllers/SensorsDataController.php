<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorsData;
use Illuminate\Support\Facades\Validator;


class SensorsDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sensordata = SensorsData::paginate(20);
        if (count($sensordata)==0) {
            $sensordata=[];
            $message = 'Aucune donnee de capteur trouvée dans la base';
        }else {
            $message = "Tous les donnee capteur";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "sensordata"=> $sensordata,    
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
        $sensordata = new SensorsData; 
        $data = $request->only('name', 'type', 'date', 'hour', 'sensors_id', 'animal_id');
        $validator=Validator::make($data, [
            'name'=>'required|string',
            'type' => 'required|string',
            'date' => 'required|date',
            'hour' => 'required|string',
            'sensors_id' => 'required|integer',
            'animal_id'=>'required|integer',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        extract($data); 
        $sensordata->name = $name;   
        $sensordata->type = $type;  
        $sensordata->date =$date;  
        $sensordata->hour = $hour; 
        $sensordata->sensors_id = $sensors_id;  
        $sensordata->animal_id = $animal_id;
        try {
            $sensordata->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette donnée du capteur existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }      
          return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de données du capteur reussie",         
                    "sensordata"=> $sensordata,    
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
        $sensordata = SensorsData::where('id', $id)->get();
        
        if (!$sensordata) {
            $sensordata=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "sensordata"=> $sensordata,
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
        $sensordata = SensorsData::find($id);
        if (!$sensordata) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet capteur de donnee n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'type', 'date', 'time', 'sensors_id', 'animal_id');
        $sensordata->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du capteur reussi',       
                'sensordata'=> $sensordata,
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
        $sensordata = SensorsData::find($id);
        if (!$sensordata) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet capteur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$sensordata->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'sensordata'=> $sensordata
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'sensordata'=> $sensordata
              ]   
        ], 404);
    }
}
