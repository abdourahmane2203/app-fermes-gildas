<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Support\Facades\Validator;


class FarmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $farms = Farm::paginate(20);
        if (count($farms)==0) {
            $farms=[];
            $message = 'Aucune ferme trouvée dans la base';
        }else {
            $message = "Tous les fermiers";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "farm"=> $farms,    
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
        $farm = new Farm; 

        $data = $request->only('name', 'phone', 'region', 'address', 'email', 'password_admin', 'herdsize', 'profile');
        
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'phone' => 'required|string|min:9|max:12',
            'region' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string|email|unique:farms',
            'password_admin' => 'required|string|min:8',
            'herdsize' => 'required|string',
            'profile'=>'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (preg_match('#[^0-9]#', $data['phone'])) {
            return response()->json([
                'success' => false,
                'phone' => 'cette attribut ne peut pas prendre des caracteres',
            ], 403);
        }
        extract($data);
        $farm->name = $name;    
        $farm->phone = $phone;  
        $farm->region = $region; 
        $farm->address = $address;
        $farm->email = $email;
       // $farm->password_admin = bcrypt($password_admin);
        $farm->herdsize  = $herdsize; 
        //validate
        try {
            $isInserted = $farm->save();
            if ($isInserted) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->farm_id = $farm->id;
                $user->password = bcrypt($password_admin);
                $user->profile = $profile;
                $user->save();
            }
        }catch (\Throwable $th) {
           // dd($th);
            return response()->json([ 
                "result" => [    
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette ferme existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        }
        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de ferme reussie",         
                    "farm"=> $farm,    
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
        $farm = Farm::where('id', $id)->get();
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "farm"=> $farm,
           ]       
       ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
        $farm = Farm::find($id);        
        if (!$farm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette ferme n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'phone', 'region', 'address', 'email', 'password_admin', 'herdsize', 'profile');
        extract($data);
       
        $farm->name = $name;    
        $farm->phone = $phone;  
        $farm->region = $region; 
        $farm->address = $address;
        $farm->email = $email;
        $farm->herdsize  = $herdsize; 
        
        try {
            $isUpdate = $farm->save();
            
            if ($isUpdate) {
                $user = User::where('farm_id', $farm->id)->get();
                
                $new_user=User::find( $user[0]['id']);
                $new_user->name=$farm->name;
                $new_user->email=$farm->email;
                $updatedUser = $new_user->save();
 
                if($updatedUser){

                    return response()->json([ 
                        "result" => [
                             "status" => "success",        
                             "message" => "Mise à jour reussie",
                             'user' => $new_user,           
                        ]   
                    ], 200);
                }
            } 

    }
    catch (\Throwable $th) {
      
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette ferme n'est pas encore modifier dans user",           
                ]   
            ], 500);  
        }
         
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour de la ferme reussi',       
                'ferme'=> $farm,
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
        $farm = Farm::find($id);
        if (!$farm) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cette ferme n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$farm->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'supplier'=> $farm
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'supplier'=> $farm
              ]   
        ], 500);
    }
}
