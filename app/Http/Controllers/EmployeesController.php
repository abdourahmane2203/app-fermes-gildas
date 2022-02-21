<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $employee = Employee::paginate(20);
        if (count($employee)==0) {
            $employee=[];
            $message = 'Aucun employé trouvée dans la base';
        }else {
            $message = "Tous les employes";
        }
        return response()->json([ 
            "result" => [
                 "status" => "success",        
                 "message" => $message,         
                 "employee"=> $employee,    
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $employee = new Employee; 
        $data = $request->only('name', 'phone' , 'address', 'email', 'region', 'salary', 'fonction_id', 'category_id', 'supervisor', 'password','contracttype_id', 'farm_id', 'profile');
        $validator=Validator::make($data, [
            'name'=>'required|string|min:3',
            'phone' => 'required|string|min:9|max:12',
            'address' => 'required|string',
            'email' => 'required|string|unique:employees|email',
            'region' => 'required|string',
            'salary'=> 'required|integer',
            'fonction_id' => 'required|integer',
            'category_id' => 'required|integer',
            'supervisor' => 'required|string',
            'password' => 'required|string|min:8',
            'contracttype_id' => 'required|integer',
            'farm_id' => 'required|integer',
            'profile' => 'required|string'

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
        
        $employee->name = $name;   
        $employee->phone = $phone;
        $employee->address = $address;
        $employee->email = $email;
        //$employee->password = bcrypt($password);
        $employee->region = $region;
        $employee->salary = $salary;
        $employee->fonction_id = $fonction_id;
        $employee->category_id = $category_id;
        $employee->supervisor = $supervisor;
        //$employee->password = $password;
        $employee->contracttype_id = $contracttype_id;
        $employee->farm_id = $farm_id;
        
        try {
            $isInserted = $employee->save();
            if ($isInserted) {
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->employee_id = $employee->id;
                $user->password = bcrypt($password);
                $user->profile = $profile;
                $user->save();
        } 
    }catch (\Throwable $th) {
            return response()->json([ 
                "result" => [
                     "status" => "faillure",        
                     "message" => "Une erreur est survenue, cet employé existe déjà dans notre système. Veuillez réessayer ou contacter l'administrateur",           
                ]   
            ], 500);  
        } 
        $employee['password'] = null;       
        return response()->json([ 
               "result" => [
                    "status" => "success",        
                    "message" => "creation de employe reussie",         
                    "employee"=> $employee,  
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
        $employee = Employee::where('id', $id)->get();
        
        if (!$employee) {
            $employee=null; 
        }
        
        return response()->json([ 
           "result" => [
                "status" => "success",               
                "employee"=> $employee,
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
        $employee = Employee::find($id);
        
        if (!$employee) {
            return response([  
                "result" => [
                    'status' => "faillure",           
                   'message'=> 'Cet employe n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $data = $request->only('name', 'phone' , 'address', 'email', 'region', 'salary', 'fonction_id', 'category_id', 'supervisor','contracttype_id', 'farm_id');
        extract($data);
       
         
        $employee->name = $name;   
        $employee->phone = $phone;
        $employee->address = $address;
        $employee->email = $email;
        $employee->region = $region;
        $employee->salary = $salary;
        $employee->fonction_id = $fonction_id;
        $employee->category_id = $category_id;
        $employee->supervisor = $supervisor;
        $employee->contracttype_id = $contracttype_id;
        $employee->farm_id = $farm_id;
        
        try {
            $isUpdate = $employee->save();
            
            if ($isUpdate) {
                $user = User::where('employee_id', $employee->id)->get();
                
                $new_user=User::find( $user[0]['id']);
                $new_user->name=$employee->name;
                $new_user->email=$employee->email;
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
                     "message" => "Une erreur est survenue, cet employé n'set pas encore modifier dans user",           
                ]   
            ], 500);  
        }
         
         return response([  
             "result" => [
                'status' => "success",           
                'message'=> 'mise a jour du employé reussi',       
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
        $employee = Employee::find($id);
        if (!$employee) {
            return response([  
                "result" => [
                   'status' => "faillure",           
                   'message'=> 'Cet employe n\'exite pas dans la base',       
                ]
           ], 403); 
        }
        $is_deleted=$employee->delete();
        if ($is_deleted) {
            return response([  
                'result' => [
                    'status'=>'success',        
                    'message'=> 'suppression reussie',
                    'employee'=> $employee
                ]   
            ], 200);
        }
        return response([          
              'result' => [
                'status'=>'faillure',      
                'message'=> 'erreur suppression, veuillez réessayer!!!',
                'employee'=> $employee
              ]   
        ], 404);
    }
}
