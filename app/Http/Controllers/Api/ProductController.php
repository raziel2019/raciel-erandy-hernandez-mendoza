<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){

        $products = Product::all();

        if($products->isEmpty()){
            $data = [
                'message' => 'No se encontraron los productos',
                'status' => 200
            ];
        }

        $data = [
            'products' => $products,
            'status' => 200
        ];

        return response()->json($data,200);
    }
    
    public function store(Request $request){
    
       $validator = Validator::make($request->all(),[
            'products' => 'required|array',
            'products.*.name' => 'required|max:255',
            'products.*.description' => 'required|max:255',
            'products.*.height' => 'required|numeric|min:0',
            'products.*.length' => 'required|numeric|min:0',
            'products.*.width' => 'required|numeric|min:0',
            ]);

            if($validator->fails()){
                $data = [
                    'message' => 'Error en la validacion de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data,400);
            }

            $products = $request->input('products');

            Product::insert($products);

            if(!$products){
                $data = [
                    'message' => "error al crear los productos",
                    'status' => 500
                ];
                return response()->json($data,500);
            }
            $data = [
                'message' => $products,
                'status' => 201
            ];
            return response()->json($data,201);
    }

    public function show($id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => "error al buscar el producto",
                'status' => 404            
            ];
            return response()->json($data,404);
        }

        $data = [
            'message' => $product,
            'status' => 201,
        ];

        return response()->json($data,201);
    }

    public function update( Request $request, $id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => "error al actualizar el producto",
                'status' => 404            
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'name'  => 'required|max:255', 
            'description' => 'required|max:255',
            'height' => 'required|numeric|min:0', 
            'length' => 'required|numeric|min:0', 
            'width'  => 'required|numeric|min:0'
            ]);

            if($validator->fails()){
                $data = [
                    'message' => 'Error en la validacion de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data,400);
            }
        
        $product->update($request->all());

        $data = [
            'message' => "producto actualizado",
            'product' => $product,
            'status' => 200,
        ];

        return response()->json($data,200);
    }

    public function updatePartial( Request $request, $id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => "error al buscar el producto",
                'status' => 404            
            ];
            return response()->json($data,404);
        }

        $validator = Validator::make($request->all(),[
            'name'  => 'max:255', 
            'description' => 'max:255',
            'height' => 'numeric|min:0', 
            'length' => 'numeric|min:0', 
            'width'  => 'numeric|min:0'
            ]);

            if($validator->fails()){
                $data = [
                    'message' => 'Error en la validacion de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data,400);
            }

            if($request->has('name')){
                $product->name  = $request->name;           
            }

            if($request->has('description')){
                $product->description  = $request->description;           
            }

            if($request->has('height')){
                $product->height  = $request->height;           
            }

            if($request->has('length')){
                $product->length  = $request->length;           
            }

            if($request->has('width')){
                $product->width  = $request->width;           
            }
        
        $product->save();

        $data = [
            'message' => "producto actualizado",
            'producto' => $product,
            'status' => 200,
        ];

        return response()->json($data,200);
    }

    public function destroy($id){
        $product = Product::find($id);
        if(!$product){
            $data = [
                'message' => "error al buscar el producto",
                'status' => 404            
            ];
            return response()->json($data,404);
        }

        $product->delete();
        
        $data = [
            'message' => "Producto eliminado",
            'status' => 200,
        ];

        return response()->json($data,200);
    }

}
