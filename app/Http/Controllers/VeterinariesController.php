<?php

namespace App\Http\Controllers;
use App\Models\Veterinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class VeterinariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $veterinary = Veterinary::paginate(20);
        if (count($veterinary)==0) {
            $veterinary=[];
            $message = 'Aucun veterinaire trouvée dans la base';
        }else {
            $message = "Tous les veterinaires";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "veterinary"=> $veterinary,    
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
        $veterinary = new Veterinary; 
        $data = $request->only('name', 'phone' , 'email', 'address', 'password', 'region', 'profile');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'phone' => 'required|string|min:9|max:12',
            'email' => 'required|string|unique:visitors|email',
            'address' => 'required|string',
            'password' => 'required|string|min:8',
            'region' => 'required|string',
            'profile' =>'required|string',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        extract($data); 
        if (preg_match('#[^0-9]#', $phone)) {
            return response()->json([
                'success' => false,
                'phone' => 'cette attribut ne peut pas prendre des caracteres',
            ]);
        }
        
        $veterinary->name = $name;   
        $veterinary->phone = $phone;
        $veterinary->email = $email;
        $veterinary->address = $address;
        $veterinary->region = $region;
        try {
            $isInserted = $veterinary->save();
            if ($isInserted) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->veterinary_id = $veterinary->id;
                $user->password = bcrypt($password);
                $user->profile = $profile;
                $user->save();
        } 
    }catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, ce veterinaire existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        } 
        $veterinary['password'] = null;       
        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de veterinaire reussie",         
                    "veterinary"=> $veterinary,  
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
        $veterinary = Veterinary::where('id', $id)->get();
        
        if (!$veterinary) {
            $veterinary=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "veterinary"=> $veterinary,
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
        $veterinary = Veterinary::find($id);        
        if (!$veterinary) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet veterinaire n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'phone', 'email', 'address', 'region_');
        extract($data);
       
        $veterinary->name=$name;
        $veterinary->phone=$phone;
        $veterinary->email=$email;
        $veterinary->address=$address;
        $veterinary->region=$region_;
        
        try {
            $isUpdate = $veterinary->save();
            
            if ($isUpdate) {
                $user = User::where('veterinary_id', $veterinary->id)->get();
                $new_user=User::find( $user[0]['id']);
                $new_user->name=$veterinary->name;
                $new_user->email=$veterinary->email;
                $updatedUser = $new_user->save();
 
                if($updatedUser){

                    return response()->json([ 
                        "result" => [
                             "status" => "success",        
                             "message" => "Mise à jour reussie",
                             'user' => $new_user,           
                        ]   
                    ], 200);
                }else{
                    return response()->json([ 
                        "result" => [
                             "status" => "faillure",        
                             "message" => "echec de modification du veterinaire",  
                        ]         
                        ], 500);
                }

            } 

    }
    catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, ce veterinaire n'est pas encore modifier dans user",           
                ]    
            ], 500);   
        } 
          
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
        $veterinary = Veterinary::find($id); 
        if (!$veterinary) { 
            return response([   
                "result" => [ 
                   'status' => "faillure",             
                   'message'=> 'Cette veterinaire n\'exite pas dans la base',        
                ]
           ], 403); 
        }
        $is_deleted=$veterinary->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'veterinary'=> $veterinary
                ]   
            ], 204);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'veterinary'=> $veterinary
                
              ]   
        ], 404);
    }
}