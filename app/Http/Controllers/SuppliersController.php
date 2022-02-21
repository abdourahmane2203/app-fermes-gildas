<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::paginate(20);
        if (count($suppliers)==0) {
            $suppliers=[];
            $message = 'Aucun fournisseur trouvé dans la base';
        }else {
            $message = "Tous les fournisseurs";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "supplier"=> $suppliers,    
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
        $supplier = new Supplier; 
        $data = $request->only('name', 'region', 'description', 'address', 'phone', 'email');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'region' => 'required|string',
            'description'=>'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|min:3|max:12',
            'email' => 'required|string|unique:suppliers|email',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (preg_match('#[^0-9]#', $data['phone'])) {
            return response()->json([
                'success' => false,
                'phone' => 'cette attribut ne peut pas prendre des caracteres',
            ]);
        }
        extract($data);
        $supplier->name = $name;   
        $supplier->region = $region;  
        $supplier->description =$description;  
        $supplier->address = $address; 
        $supplier->phone = $phone;  
        $supplier->email = $email;

        try {
            $supplier->save();
        } catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet supperviseur existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  

        }       
        
             return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de fournisseur reussie",         
                    "supplier"=> $supplier,    
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
        $id = intval($id);
        $supplier = Supplier::where('id', $id)->get();
        
        if (!$supplier) {
            $supplier=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "supplier"=> $supplier,
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
        $supplier = Supplier::find($id);
       
        if (!$supplier) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette fournisseur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        
        $data = $request->only('name', 'region', 'description', 'address', 'phone', 'email');
        $supplier->update($data); 
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du fournisseur reussi',       
                'supplier'=> $supplier,
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
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette fournisseur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$supplier->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'supplier'=> $supplier
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'supplier'=> $supplier
              ]   
        ], 500);
    }
}
