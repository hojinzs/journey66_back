<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Option;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OptionController extends Controller
{
    public function list()
    {
        $tables = Option::query()
            ->select('table')
            ->groupBy('table')
            ->orderBy('table')
            ->get();

        $tableArray = [];
        foreach ($tables as $table){
            array_push($tableArray,$table->table);
        }

        return $tableArray;
    }

    public function create(Request $request)
    {
        $table = $request->input('table');
        $column = $request->input('column');

        return $this->store($table, $column, $request);
    }

    /**
     * @param $table
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($table,$column)
    {
        $tables = Option::query()
            ->select('id','table','column','option','label','order','show')
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
    public function store($table, $column, Request $request)
    {
        $option = new Option();

        $this->ValidateTableColumn($table,$column);
        $this->ValidateRequest($table,$column,$request);

        $option->table = $table;
        $option->column = $column;
        $option->option = $request->input('option');
        $option->label = $request->input('label');
        $option->save();

        return $option;
    }

    public function update($table, $column, $id, Request $request)
    {
        $option = Option::query()
            ->where('id',$id)
            ->where('table',$table)
            ->where('column',$column)
            ->first();

        if(!$option){
            return response('invalid option '.$option,404);
        }

        $this->ValidateRequest($table,$column, $request, $option->id);

        $option->option = $request->input('option');
        $option->label = $request->input('label');
        $option->save();

        return $option;
    }

    public function order($table,$column, Request $request)
    {
        // 정렬 전, 정렬 대상 테이블-컬럼의 데이터를 가져옴
        $options = Option::query()
            ->where('table',$table)
            ->where('column',$column)
            ->get();

        if(!$options){
            return response('invalid option',404);
        }

        $optionArrays = [];
        foreach ($options as $option) {
            array_push($optionArrays,$option->option);
        }
        $orders = $request->input('order');

        Validator::make([
            'order' => $orders
        ],[
            'order' => [
                'required',
                'array',
                // 주어진 value가 실제로 option에 존재하는지 확인
                'in' => Rule::in($optionArrays),
            ]
        ],[
            'in' => 'the :attribute must be one of the following types: :values'
        ])->validate();

        // 모든 옵션의 order, show 상태를 초기화
        $options->each(function($option) {
            $option->order = null;
            $option->show = 0;
        });

        // 배열 순서에 따라 순서를 매기고 디스플레이 상태를 부여
        foreach ($orders as $key => $order) {
            $option = $this->findOptionInArray($order,$options);
            $option->order = $key+1;
            $option->show = 1;
        }

        // 저장
        $options->each(function($option){
            $option->save();
        });

        return $options;
    }

    /**
     * @param $table
     * @param $column
     * @param $option
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($table,$column,$option)
    {
        $option = Option::query()
            ->where('table',$table)
            ->where('column',$column)
            ->where('option',$option)
            ->first();

        if(!$option){
            return response('invalid option '.$option,404);
        }

        Validator::make([
            'show' => $option->show
        ],[
            'show' => function($attribute, $value, $fail){
                if($value === 1){
                    $fail('삭제 전, 옵션을 숨김 상태로 먼저 변경해 주세요');
                }
            }
        ])->validate();

        $option->delete();
        return response($table.'.'.$column.' option "'.$option->option.'" delete success',200);

    }

    private function ValidateRequest($table, $column, Request $request, $ignore = null)
    {
        $request->validate([
            'option' => [
                'required',
                'alpha',
                'max:20',
                Rule::unique('options')->where(function($query) use ($table,$column){
                    return $query->where('table',$table)
                        ->where('column',$column);
                })->ignore($ignore),
            ],
            'label' => [
                'required',
                'string',
                'max:100'
            ],
        ]);
    }

    private function ValidateTableColumn($table, $column)
    {
        Validator::make([
            'table' => $table,
            'column' => $column
        ],[
            'table' => [
                'required',
                'exist' => function($attribute, $value, $fail){
                    if(Schema::hasTable($value) == false){
                        $fail('table '.$value.' is not exist');
                    };
                }
            ],
            'column' => [
                'required',
                'exist' => function($attribute, $value, $fail) use ($table){
                    if(Schema::hasColumn($table, $value) == false){
                        $fail('column '.$value.' in table '.$table.' is not exist');
                    }
                }
            ],
        ])->validate();
    }

    private function findOptionInArray($value, $optionArray)
    {
        $find = false;
        foreach ($optionArray as $option) {
            if($option->option == $value) {
                $find = $option;
            }
        }
        return $find;
    }
}
