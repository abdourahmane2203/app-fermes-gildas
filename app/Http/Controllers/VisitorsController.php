<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VisitorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $visitor = Visitor::paginate(20);
        if (count($visitor)==0) {
            $visitor=[];
            $message = 'Aucun visiteur trouvée dans la base';
        }else {
            $message = "Tous les visiteurs";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "visitor"=> $visitor,    
            ]   
        ], 201);
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
     * Store a newly created resource in storage. :;,
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $visitor = new Visitor; 
        $data = $request->only('name', 'phone', 'address', 'email', 'password', 'region', 'membership', 'profile');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'phone' => 'required|string|min:9|max:12',
            'address' => 'required|string',
            'email' => 'required|string|unique:visitors|email',
            'password' => 'required|string|min:8',
            'region' => 'required|string',
            'membership' => 'required|boolean',
            'profile' => 'required|string',

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
        // Validation de données / add region attribute  
        
        $visitor->name = $name;   
        $visitor->phone = $phone;
        $visitor->address = $address;
        $visitor->email = $email;
        //$visitor->password = bcrypt($password);
        $visitor->region = $region;
        $visitor->membership = $membership;
        
        try {
            $isInserted = $visitor->save();
            if ($isInserted) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->visitor_id = $visitor->id;
                $user->password = bcrypt($password);
                $user->profile = $profile;
                $user->save();
        } 
    }catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cette visiteur existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        } 
        $visitor['password'] = null;       
        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de visitor reussie",         
                    "visitor"=> $visitor,  
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
        $visitor = Visitor::where('id', $id)->get();
        
        if (!$visitor) {
            $visitor=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "visitor"=> $visitor,
           ]       
       ], 200);
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
        $visitor = Visitor::find($id);
        
        if (!$visitor) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet visiteur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
       // dd($visitor);
        $data = $request->only('name', 'phone', 'address', 'email', 'region', 'membership', 'profiles');
        extract($data);
       
        $visitor->name=$name;
        $visitor->phone=$phone;
        $visitor->address=$address;
        $visitor->email=$email;
        $visitor->region=$region;
        $visitor->membership=$membership;
        
        try {
            $isUpdate = $visitor->save();
            //dd($isUpdate);
            
            if ($isUpdate) {
                $user = User::where('visitor_id', $visitor->id)->get();
                
                $new_user=User::find( $user[0]['id']);
                $new_user->name=$visitor->name;
                $new_user->email=$visitor->email;
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
                     "message" => "Une erreur est survenue, cet visiteur n'set pas encore modifier dans user",           
                ]   
            ], 500);  
        }
         
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du visiteur reussi',       
                'visitor'=> $visitor,
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
        $visitor = Visitor::find($id);
        if (!$visitor) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet visiteur n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$visitor->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'visitor'=> $visitor
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'visitor'=> $visitor
              ]   
        ], 404);
    }
}
