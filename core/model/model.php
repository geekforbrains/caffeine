<?php

class Model extends Db_Query {

    /**
     * Converts model objects to an array where a models fields are the array key and
     * the field value is the array value.
     *
     * This will only return the properties in the model associated with the models table.
     */
    public static function toArray($model)
    {
        $data = array();

        if(is_array($model))
        {
            foreach($model as $m)
                $data[] = self::toArray($m);
        }
        else
        {
            $info = $model->describe();

            foreach($info as $i)
                $data[$i->Field] = $model->{$i->Field};
        }

        return $data;
    }

}
