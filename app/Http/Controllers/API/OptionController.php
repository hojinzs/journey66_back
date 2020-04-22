<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Option;
use Illuminate\Http\Request;

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
