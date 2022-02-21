<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplierFarm;
use Illuminate\Support\Facades\Validator;

class SupplierFarmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $supfarm = SupplierFarm::paginate(20);
        if (count($supfarm)==0) {
            $supfarm=[];
            $message = 'Aucun enregistrement trouvé dans la base';
        }else {
            $message = "Tous les fournisseuenregistrementsrs";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "supfarm"=> $supfarm,    
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
        $supfarm = new SupplierFarm; 
        $data = $request->only('supplier_id','farm_id');
        $validator=Validator::make($data, [
            'supplier_id' => 'required|integer',
            'farm_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        extract($data);
        $supfarm->supplier_id = $supplier_id;   
        $supfarm->farm_id = $farm_id;

        try {
            $supfarm->save();
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
                    "message" => "creation de ferme supperviseur reussie",         
                    "supfarm"=> $supfarm,    
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
        $supfarm = SupplierFarm::where('id', $id)->get();
        
        if (!$supfarm) {
            $supfarm=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "supfarm"=> $supfarm,
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
        $supfarm = SupplierFarm::find($id);
       
        if (!$supfarm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette enregistrement n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        
        $data = $request->only('supplier_id','farm_id');
        $supfarm->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour enregistrement reussi',       
                'supfarm'=> $supfarm,
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
        $supfarm = SupplierFarm::find($id);
        if (!$supfarm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet enregistrement n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$supfarm->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'supfarm'=> $supfarm
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'supfarm'=> $supfarm
              ]   
        ], 500);
    }
}
