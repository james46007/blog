<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;

class CategoryController extends Controller
{

    public function __construct(){
        $this->middleware('api.auth',['except' => ['index','show']]);
    }

    public function index(){
        $categories = Category::all();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'categories' => $categories
        ],200);
    }

    public function show($id){
        $category = Category::find($id);

        if(is_object($category)){
            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        }else{
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La categoria no existe'
            ];
        }
        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){

        $validate = \Validator::make($params_array, [
            'name' => 'required'
        ]);

        if($validate->fails()){
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'No se ha guardado la categoria'
            ];
        }else{
            $category = new Category();
            $category->name = $params_array['name'];
            $category->save();

            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        }
        }else{
            $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'No has enviado ninguna categoria.'
                ];
        }

        return response()->json($data,$data['code']);
    }

    public function update($id,Request $request){

        $json = $request->input('json',null);
        $params_array = json_decode($json, true);

        if(!empty($params_array)){

        //validar datos
        $validate = \Validator::make($params_array, [
            'name' => 'required'
        ]);

        //no se actualizara estos campos
        unset($params_array['id']);
        unset($params_array['created_at']);

        $category = Category::where('id',$id)->update($params_array);

        $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];

        }else{
            $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'No has enviado ninguna categoria.'
                ];
        }

        return response()->json($data,$data['code']);

    }
}
