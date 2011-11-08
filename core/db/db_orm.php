<?php 

class Db_Orm extends Db_Query {

    // Keeps track of how foreign keys are associated
    public $_hasOne = array();
    public $_hasMany = array();
    public $_belongsTo = array();
    public $_hasAndBelongsToMany = array();

    public $_timestamps = false;
    public $_indexes = array();
    public $_fulltext = array();

    // Combine _hasMany and _hasOne because their the same thing
    public function __construct($table = null)
    {
        $this->_hasMany = array_merge($this->_hasMany, $this->_hasOne);
        parent::__construct($table);
    }

    /**
     * All object based method calls will be assumed to be another (related) table/model.
     *
     * NOTE that this class eventually extends Module, which implements __callStatic. Anything
     * that extends this class (or Model) should not implement __call or __callStatic.
     */
    public function __call($name, $args)
    {
        // blog.post has many blog.comment
        // Blog::post()->find(1)->comment()->first();
        if($this->_hasMany)
        {
            foreach($this->_hasMany as $refModel)
            {
                $refBits = explode('.', $refModel);

                if($refBits[1] == $name)
                {
                    $currBits = explode('_', $this->tableName);
                    $refClass = sprintf('%s_%sModel', ucfirst($refBits[0]), ucfirst($refBits[1]));
                    $refKey = sprintf('%s_id', String::singular($currBits[1]));

                    $refModel = new $refClass();
                    return $refModel->where($refKey, '=', $this->id);
                }
            }
        }

        // Check if has _belongsTo, if it does select via left join to reference table
        if($this->_belongsTo)
        {
            foreach($this->_belongsTo as $refModel)
            {
                $refBits = explode('.', $refModel); // module.model = array(module, model)

                if($refBits[1] == $name)
                {
                    $refClass = sprintf('%s_%sModel', ucfirst($refBits[0]), ucfirst($refBits[1])); // Module_ModelModel
                    $refTable = sprintf('%s_%s', $refBits[0], String::plural($refBits[1])); // module_models
                    $refKey = $refBits[1] . '_id'; // model_id

                    $refModel = new $refClass();
                    return $refModel->select($refTable . '.*')
                        ->leftJoin($this->tableName, $refTable . '.id', '=', $this->tableName . '.' . $refKey)
                        ->where($this->tableName . '.id', '=', $this->id);
                }
            }
        }

        if($this->_hasAndBelongsToMany)
        {
            foreach($this->_hasAndBelongsToMany as $refModel)
            {
                $refBits = explode('.', $refModel);

                if($refBits[1] == $name)
                {
                    $refClass = sprintf('%s_%sModel', ucfirst($refBits[0]), ucfirst($refBits[1]));
                    $refTable = sprintf('%s_%s', $refBits[0], String::plural($refBits[1]));

                    $currBits = explode('_', $this->tableName);

                    // Key table (the table that joins the two tables)
                    $keyNames = array(String::plural($refBits[1]), $currBits[1]);
                    sort($keyNames);
                    $keyTable = implode('_', $keyNames);

                    $key1 = $refBits[1] . '_id';
                    $key2 = String::singular($currBits[1]) . '_id';

                    $refModel = new $refClass();
                    return $refModel->select($this->tableName . '.*')
                        ->leftJoin($keyTable, $keyTable . '.' . $key1, '=', $this->tableName . '.id')
                        ->where($keyTable . '.' . $key2, '=', $this->id);
                }
            }
        }
    }

    /**
     * Handles saving current items that have been updated, as well as creating new ones.
     */
    public function save()
    {
        $data = array();
        foreach($this->fieldNames as $field)
        {
            if($field == 'id') // Id's are always auto_increment, ignore them
                continue;

            $data[$field] = $this->{$field};

            // Check if _belongsTo
            if($this->_belongsTo)
            {
                // Ex: comments belong to posts (model = post)
                foreach($this->_belongsTo as $refModel)
                {
                    $refBits = explode('.', $refModel);
                    $property = $refBits[1];

                    //$id = (is_int($this->{$property})) ? $this->{$property} : $this->{$property}->id; // Value might be int or instance
                    $id = $this->_getPropertyId($this->{$property});
                    $data[$refBits[1] . '_id'] = $id;
                }
            }
        }

        // Do insert or update
        $status = false;
        if(is_null($this->id))
        {
            // If timestamps are enabled, add them to the data to be inserted
            if($this->_timestamps)
            {
                $timestamp = time();
                $data['created_at'] = $timestamp;
                $data['updated_at'] = $timestamp;

            }

            if($id = $this->insert($data, true))
            {
                $this->id = $id;
                $status = true;
            }
        }
        else
        {
            // If timestamps are enabled, update created_at only
            if($this->_timestamps)
                $data['updated_at'] = time();

            $status = $this->where('id', '=', $this->id)->update($data);
        }

        // Check if _hasAndBelongsToMany, running update if necessary
        if($this->_hasAndBelongsToMany)
        {
            foreach($this->_hasAndBelongsToMany as $refModel)
            {
                $refBits = explode('.', $refModel); // blog.category
                $currBits = explode('_', $this->tableName); // blog_posts = array(blog, posts)

                $property = String::plural($refBits[1]); // category = categories

                $keyNames = array($currBits[1], $property);
                sort($keyNames);
                $keyTable = implode('_', $keyNames); // categories_posts

                $refKey1 = String::singular($currBits[1]) . '_id'; // post_id
                $refKey2 = $refBits[1] . '_id'; // category_id

                // Check if property was set and has data
                if(isset($this->{$property}))
                {
                    // If false, empty string or empty array, delete records associated with this model
                    if(!$this->{$property})
                    {
                        $tableId = String::singular($currBits[1]) . '_id';
                        Db::table($keyTable)->where($tableId, '=', $this->id)->delete();
                    }
                    else
                    {
                        // Check if array, if it is, might be an array of int id's or array of model objects
                        if(is_array($this->{$property}))
                        {
                            foreach($this->{$property} as $v)
                            {
                                //$id = (is_int($v)) ? $v : $v->id; // Might be an array of ids, or a model object 
                                $id = $this->_getPropertyId($v);

                                Db::table($keyTable)->insert(array(
                                    $refKey1 => $this->id,
                                    $refKey2 => $id
                                ));
                            }
                        }

                        // Not array, must be single int or single model object
                        else
                        {
                            //$id = (is_int($this->{$property})) ? $this->{$property} : $this->{$property}->id;
                            $id = $this->_getPropertyId($this->{$property});

                            Db::table($keyTable)->insert(array(
                                $refKey1 => $this->id,
                                $refKey2 => $id
                            ));
                        }
                    }
                }
            }
        }

        return $status;
    }

    // If id, create instance of this 
    // If no id, this must be a record object and have a set id we can use
    public function delete($id = null)
    {
        // Id passed, create instance using given id of the current model
        if(!is_null($id))
        {
            $obj = $this->where('id', '=', $id)->first();
            if($obj)
                return $obj->delete();
            return false;
        }

        // Else, has to be a record object, delete it based on its id and any referencing tables
        else
        {
            // Check if _hasMany (ex: Blog posts have many comments, so delete comments associated with post)  
            if($this->_hasMany)
            {
                foreach($this->_hasMany as $refModel)
                {
                    // blog.comment => DELETE FROM blog_comments WHERE post_id = ?
                    $currBits = explode('_', $this->tableName); // array('blog', 'posts')
                    $currId = String::singular($currBits[1]) . '_id';

                    $refBits = explode('.', $refModel);
                    $refClass = sprintf('%s_%sModel', ucfirst($refBits[0]), ucfirst($refBits[1]));
                    
                    $refObj = new $refClass();
                    $rows = $refObj->where($currId, '=', $this->id)->get();

                    // Recursively delete any records, recursive incase the objects we're deleting also have references
                    if($rows)
                        foreach($rows as $row)
                            $row->delete();
                }
            }

            // Check if _hasAndBelongsToMany
            if($this->_hasAndBelongsToMany)
            {
                // blog.categories => DELETE FROM categories_posts WHERE post_id = ?
                foreach($this->_hasAndBelongsToMany as $refModel)
                {
                    $refBits = explode('.', $refModel);

                    $currBits = explode('_', $this->tableName); // array('blog', 'posts')
                    $currKeyId = String::singular($currBits[1]) . '_id';

                    $keyNames = array(String::plural($refBits[1]), $currBits[1]);
                    sort($keyNames);
                    $keyTable = implode('_', $keyNames);

                    Db::table($keyTable)->where($currKeyId, '=', $this->id)->delete();
                }
            }

            // Delete actual record
            parent::delete($this->id);
        }

    }

    public function createTable()
    {
        Db_Installer::install($this);
    }

    // Determines if the property given is an object or int (might be string) which is converted to int
    private function _getPropertyId($var) {
        return (is_object($var)) ? $var->id : intval($var);
    }

}
