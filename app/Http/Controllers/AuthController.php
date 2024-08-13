<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        /*
        NOTA: img_profile solo es para que se suba el texto de la imagen
         no para almacenar ningun archivo esto por temas de espacio en mi servidor

         Si asi lo desea es necesario conexion con un bucket de imagenes para almacenar los archivos
        */
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'integer', 'min:8'],
            'img_profile' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'img_profile' => $request->img_profile,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            $data = [
                'message' => "Error al crear el usuario",
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'message' => 'Usuario creado con éxito',
            'user' => $user,
            'token' => $token,
            'status' => 201
        ];
        return response()->json($data, 201);
    }


    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            ]);

            if($validator->fails()){
                $data = [
                    'message' => 'Error en la validacion de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data,400);
            }
      
            $user = User::where('email', $request['email'])->first();
            if(!$user || !Hash::check($request['password'], $user->password)){
                $data = [
                    'message' => 'Credenciales erroneas',
                    'status' => '401'
                ];
                return response()->json($data,4001);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

                $data = [
                    'message' => 'success',
                    'user' => $user,
                    'token' => $token,
                    'status' => 201
                ];
                return response()->json($data,201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Usted se ha deslogeado'], 200);
    }

}