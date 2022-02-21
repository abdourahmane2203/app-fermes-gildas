<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VeterinaryFarm;
use Illuminate\Support\Facades\Validator;


class VeterinaryFarmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vetfarm = VeterinaryFarm::paginate(20);
        if (count($vetfarm)==0) {
            $vetfarm=[];
            $message = 'Aucun enregistrement trouvé dans la base';
        }else {
            $message = "Tous les fournisseuenregistrementsrs";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "vetfarm"=> $vetfarm,    
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
        $vetfarm = new VeterinaryFarm; 
        $data = $request->only('veterinary_id','farm_id');
        $validator=Validator::make($data, [
            'veterinary_id' => 'required|integer',
            'farm_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        extract($data);
        $vetfarm->veterinary_id = $veterinary_id;   
        $vetfarm->farm_id = $farm_id;

        try {
            $vetfarm->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet enregistrement existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  

        }       
        
             return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation est reussie",         
                    "vetfarm"=> $vetfarm,    
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
        $vetfarm = VeterinaryFarm::where('id', $id)->get();
        
        if (!$vetfarm) {
            $vetfarm=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "vetfarm"=> $vetfarm,
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
        $vetfarm = VeterinaryFarm::find($id);
       
        if (!$vetfarm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette enregistrement n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        
        $data = $request->only('veterinary_id','farm_id');
        $vetfarm->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour enregistrement reussi',       
                'vetfarm'=> $vetfarm,
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
        $vetfarm = VeterinaryFarm::find($id);
        if (!$vetfarm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet enregistrement n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$vetfarm->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'vetfarm'=> $vetfarm
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'vetfarm'=> $vetfarm
              ]   
        ], 500);
    }

    public function veterinaryByFarm($farm_id)
    {
        //
        $farm_id = intval($farm_id);
        $vetfarm = VeterinaryFarm::where('farm_id', $farm_id)->get();
        
        if (!$vetfarm) {
            $vetfarm=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "vetfarm"=> $vetfarm,
           ]       
       ], 201);
    }

    public function farmBYVeterinary($veterinary_id)
    {
        //
        $veterinary_id = intval($veterinary_id);
        $vetfarm = VeterinaryFarm::where('veterinary_id', $veterinary_id)->get();
        
        if (!$vetfarm) {
            $vetfarm=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "vetfarm"=> $vetfarm,
           ]       
       ], 201);
    }
}
