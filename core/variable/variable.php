<?php

class Variable extends Module {

    public static function get($key, $default = null)
    {
        if($var = Variable::data()->where('data_key', '=', $key)->first())
            return $var->data_value;
        return $default;
    }

    public static function store($key, $value)
    {
        if(Variable::data()->where('data_key', '=', $key)->first())
        {
            Variable::data()->where('data_key', '=', $key)->update(array(
                'data_value' => $value
            ));
        }
        else
        {
            Variable::data()->insert(array(
                'data_key' => $key,
                'data_value' => $value
            ));
        }
    }

}
