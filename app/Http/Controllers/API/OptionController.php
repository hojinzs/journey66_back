<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Option;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OptionController extends Controller
{
    //
    public function dispatchTagIconPrefix(Request $request)
    {
        $newIconPrefixArray = json_decode($request->array);

        if(!is_array($newIconPrefixArray)){
            return abort(400,'array must be json');
        }

        $options = Option::query()
            ->where('table','=','tags')
            ->where('column','=','icon_prefix')
            ->get();

        foreach ($options as $option) {
            $option->delete();
        }

        foreach ($newIconPrefixArray as $newPrefix)
        {
            $this->newOption('tags','icon_prefix',$newPrefix);
        }

        return $this->getTagIconPrefix();

    }

    public function getTagIconPrefix()
    {
        $options = Option::query()
            ->where('table','=','tags')
            ->where('column','=','icon_prefix')
            ->get();

        return $this->toArray($options);
    }

    /**
     * @param $table
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index($table)
    {
        $tables = Option::query()
            ->select('table','column',DB::raw('COUNT(*) as options'))
            ->where('table',$table)
            ->groupBy('column')
            ->get();

        return response($tables);
    }

    /**
     * @param $table
     * @param $column
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($table,$column)
    {
        $tables = Option::query()
            ->select('table','column','option','label')
            ->where('table',$table)
            ->where('column',$column)
            ->get();

        return response($tables);
    }

    /**
     * @param Request $request
     * @param $table
     * @param $column
     * @return Option|\Illuminate\Support\MessageBag
     */
    public function store(Request $request, $table,$column)
    {
        $option = new Option();

        $validation = Validator::make([
            'table' => $table,
            'column' => $column
        ],[
            'table' => function($attribute, $value, $fail){
                if(Schema::hasTable($value) == false){
                    $fail('table '.$value.' is not exist');
                };
            },
            'column' => function($attribute, $value, $fail) use ($table){
                if(Schema::hasColumn($table, $value) == false){
                    $fail('column '.$value.' in table '.$table.' is not exist');
                }
            }
        ]);

        if($validation->fails()){
            return $validation->errors();
        }

        $request->validate([
            'option' => ['required','alpha',Rule::unique('options')->where(function($query) use ($table,$column){
                return $query->where('table',$table)
                    ->where('column',$column);
            })],
            'label' => ['required','string'],
        ]);

        $option->table = $table;
        $option->column = $column;
        $option->option = $request->input('option');
        $option->label = $request->input('label');
        $option->save();

        return $option;
    }

    /**
     * @param $table
     * @param $column
     * @param $option
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($table,$column,$option)
    {
        $option = Option::query()
            ->where('table',$table)
            ->where('column',$column)
            ->where('option',$option)
            ->first();

        if($option){
            $option->delete();
            return response($table.'.'.$column.' option "'.$option->option.'" delete success',200);
        } else {
            return response('invalid option '.$option,404);
        }
    }

    private function toArray($options)
    {
        $optionsArray = [];

        foreach ($options as $option)
        {
            array_push($optionsArray, $option->option);
        }

        return $optionsArray;
    }

    private function newOption($table, $column, $option, $label = null)
    {
        $options = new Option();
        $options->table = $table;
        $options->column = $column;
        $options->option = $option;
        if($label != null){
            $options->lable = $label;
        }
        $options->save();

        return $options;
    }
}
