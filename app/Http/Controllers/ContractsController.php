<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Validator;

class ContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $contract = Contract::paginate(20);
        if (count($contract)==0) {
            $contract=[];
            $message = 'Aucune contrat trouvée dans la base';
        }else {
            $message = "Tous les contrats";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "contract"=> $contract,    
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
        $contract = new Contract; 
        $data = $request->only('type');
        $validator=Validator::make($data, [
            'type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Validation de données / add region attribute   
        extract($data);
        $contract->type = $type; 
        try {
            $contract->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet contrat existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }      
          return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de type de contrat reussie",         
                    "contract"=> $contract,    
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
        $contract = Contract::where('id', $id)->get();
        
        if (!$contract) {
            $contract=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "contract"=> $contract,
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
        $contract = Contract::find($id);
        if (!$contract) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet contrat n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('type');
        $contract->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du contrat reussi',       
                'contract'=> $contract,
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
        $contract = Contract::find($id);
        if (!$contract) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet contrat n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$contract->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'contract'=> $contract
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'contract'=> $contract,
              ]   
        ], 404);
    }
}
