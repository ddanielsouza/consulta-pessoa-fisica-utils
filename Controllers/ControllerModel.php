<?php

namespace App\Utils\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Utils\Facades\ModelHelper;
use App\Http\Controllers\Controller;

abstract class ControllerModel  extends Controller
{
    protected $modelName;
    protected $basicValidate = [];
    protected $columnsEncrypted = [];

    public function getById(Request $request, $id)
    {
        try {
            $model = $this->modelName::where('id', $id);
            $relations = json_decode($request->input('relations'));
            if(json_last_error() == JSON_ERROR_NONE && is_array($relations)){
                $model->with($relations);
            }

            $columnsSelect = json_decode($request->input('columnsSelect'));
            if(json_last_error() == JSON_ERROR_NONE && is_array($columnsSelect)){
                $model->select($columnsSelect);
            }

            $result = $model->first();
            return response()->json(['success' => true, 'data' => $result], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), $this->basicValidate);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = new $this->modelName();
            foreach ($request->all() as $key => $value) {
                $model->$key = $value;
            }
            $model->save();
            return response()->json(['success' => true, 'data' => $model], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), $this->basicValidate);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = $this->modelName::find($id);

            if (!empty($model)) {
                foreach ($request->all() as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
                return response()->json(['success' => true, 'data' => $model], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'registro não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage(), $e->getFile(), $e->getLine()]
            ], 500);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $rules = [];
            foreach ($this->basicValidate as $key => $value) {
                $rules[$key] = preg_replace('/required\\||\\|required/im', '', $value);
            }
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => join(" - ", (array) $validator->errors()->all()),
                ], 400);;
            }

            $model = $this->modelName::find($id);

            if (!empty($model)) {
                foreach ($request->all() as $key => $value) {
                    $model->$key = $value;
                }
                $model->save();
                return response()->json(['success' => true, 'data' => $model], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'registro não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function get(Request $request)
    {
        try{
            $model = $this->modelName::whereNotNull('id');

            $relationships = json_decode($request->input('relationships'));
            if(json_last_error() == JSON_ERROR_NONE && is_array($relationships)){
                $model->with($relationships);
            }

            $columnsSelect = json_decode($request->input('columnsSelect'));
            if(json_last_error() == JSON_ERROR_NONE && is_array($columnsSelect)){
                $model->select($columnsSelect);
            }

            $paginate = json_decode($request->input('paginate'));
            if(json_last_error() == JSON_ERROR_NONE && is_object($paginate)){
                $limit = !empty($paginate->limit) ? $paginate->limit : 100;
                $offset = !empty($paginate->offset) ? $paginate->offset : 0;

                $model->limit($limit);
                $model->offset($offset);
            }

            $model = ModelHelper::filterAllColumns($model, $request->all(), $this->getRules(), $this->columnsEncrypted);

            return response()->json(['success' => true, 'data' => $model->get()], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function delete($id){
        try{
            $model = $this->modelName::find($id);

            if(!empty($model)){
                $model->delete();
            }else{
                return response()->json(['success' => false, 'message' => 'registro não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno!',
                'error' => [$e->getMessage()]
            ], 500);
        }
    }

    public function getRules(){
        return array_merge($this->basicValidate, [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
    }
}
