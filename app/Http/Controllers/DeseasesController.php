<?php

namespace App\Http\Controllers;
use App\Models\Desease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeseasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $desease = Desease::paginate(20);
        if (count($desease)==0) {
            $desease=[];
            $message = 'Aucune maladie trouvée dans la base';
        }else {
            $message = "Toutes les maladies";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "desease"=> $desease,    
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
        $desease = new Desease; 
        $data = $request->only( 'description', 'type', 'cure', 'veterinary_id');
        $validator=Validator::make($data, [
            'description'=>'required|string',
            'type' => 'required|string',
            'cure' => 'required|string',
            'veterinary_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

       
        // Validation de données / add region attribute 
        extract($data);  
        $desease->description = $description;   
        $desease->type = $type;  
        $desease->cure =$cure;  
        $desease->veterinary_id = $veterinary_id; 
        try {
            $desease->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette maladie existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation reussie",         
                    "desease"=> $desease,    
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
        $desease = Desease::where('id', $id)->get();
        
        if (!$desease) {
            $desease=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "desease"=> $desease,
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
        $desease = Desease::find($id);
        if (!$desease) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette maladie n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only( 'description', 'type', 'cure', 'veterinary_id');
        $desease->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du ferme reussi',       
                'desease'=> $desease,
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
        $desease = Desease::find($id);
        if (!$desease) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette maladie n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$desease->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'desease'=> $desease
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'desease'=> $desease
              ]   
        ], 404);
    }
}
