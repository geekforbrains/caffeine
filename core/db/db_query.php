<?php

class Db_Query extends Module {

    public $tableName       = null;
    public $fieldNames      = array();

    private $_table         = null;
    private $_class         = null;

    private $_select        = '';
    private $_distinct      = false;
    private $_from          = '';
    private $_join          = '';
    private $_where         = '';
    private $_orderBy       = '';
    private $_limit         = '';
    private $_bindings      = array();

    protected $_createId    = true;
    protected $_timestamps  = false;
    protected $_fields      = array();
    protected $_indexes     = array();
    protected $_fulltext    = array();

    protected $_hasOne      = array();
    protected $_hasMany     = array();
    protected $_belongsTo   = array();
    protected $_hasAndBelongsToMany = array();

    private static $_describes = array();

    /**
     * Allow read access to private properties.
     */
    public function __get($name) {
        return $this->{$name};
    }
    
    /**
     * TODO Comments
     */
    public function __construct($table = null)
    {
        if(!is_null($table))
            $this->tableName = $table;

        if(is_null($this->tableName))
            $this->tableName = $this->_getTableName();

        $this->_table = $this->tableName;
        $this->_from = sprintf(' FROM %s', $this->_table);

        // Produce query results that are of the same class as this model
        $this->_class = get_called_class(); 

        // Setup blank properties for this class based on table description
        $fields = $this->describe();
        if($fields && is_array($fields))
        {
            foreach($fields as $field)
            {
                $this->fieldNames[] = $field->Field;
                if(!isset($this->{$field->Field}))
                    $this->{$field->Field} = null;
            }
        }
    }

    /**
     * Adds a new table index to the end of the index array.
     */
    public function addIndex($key) {
        array_push($this->_indexes, $key);
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function select()
    {
        $args = func_get_args();

        // Only generate select if it wasn't done previously, or if new params were passed (via first(), get() etc.)
        if(!strlen($this->_select) || $args)
        {
            $this->_select = ($this->_distinct) ? 'SELECT DISTINCT ' : 'SELECT ';
            $this->_select .= ($args) ? implode(', ', $args) : '*'; 
        }

        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function insert($data, $getInsertId = true)
    {
        $columns = array();
        $values = array();

        if($this->_timestamps)
        {
            $timestamp = time();
            $data['created_at'] = $timestamp;
            $data['updated_at'] = $timestamp;
        }

        foreach($data as $c => $v)
        {
            $columns[] = $c;
            $values[] = $v;
        }

        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', 
            $this->_table, 
            implode(', ', $columns),
            rtrim(str_repeat('?, ', count($values)), ', ')
        );

        return Db::query($sql, $values, $getInsertId);
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function update($data)
    {
        $columns = array();
        $values = array();

        if($this->_timestamps)
            $data['updated_at'] = time();

        foreach($data as $c => $v)
        {
            $columns[] = $c . ' = ?';
            $values[] = $v;
        }

        $values = array_merge($values, $this->_bindings);
        $sql = sprintf('UPDATE %s SET %s %s', $this->_table, implode(', ', $columns), $this->_where);

        return Db::query($sql, $values);
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function delete($id = null)
    {
        if(!is_null($id))
            $this->where('id', '=', $id);

        if(strlen($this->_where))
            $sql = sprintf('DELETE%s%s', $this->_from, $this->_where);
        else
            $sql = sprintf('TRUNCATE TABLE %s', $this->_table);

        return Db::query($sql, $this->_bindings);
    }

    
    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function truncate()
    {
        $this->_where = '';
        return $this->delete();
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function describe() 
    {
        if(!isset(self::$_describes[$this->_table]))
        {
            $data = Db::query('DESCRIBE ' . $this->_table);
            // Only store a describe if it returns data, otherwise the table probably wasn't created yet
            if($data)
                self::$_describes[$this->_table] = $data;
            else
                return $data;
        }

        return self::$_describes[$this->_table];
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function exists() {
        return Db::query('SHOW TABLES LIKE \'' . $this->_table . '\'');
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function distinct()
    {
        $this->_distinct = true;
        return $this;
    }

    /**
     * ---------------------------------------------------------------------------   
     * Regular SELECT query. If no other options are set, this will get all
     * records from the current table.
     *
     * Can specify params of fields to get. If no params are set, all fields
     * will be returned.
     *
     * Examples:
     *
     * Db()->table('users')->get(); // Get all records and all fields
     * Db()->table('users')->get('first_name', 'age'); // Only two fields
     * ---------------------------------------------------------------------------   
     */
    public function get()
    {
        call_user_func_array(array($this, 'select'), func_get_args());

        $sql = $this->_select . 
            $this->_from . 
            $this->_join . 
            $this->_where . 
            $this->_orderBy .
            $this->_limit;

        return Db::query($sql, $this->_bindings, false, $this->_class);
    }


    /**
     * ---------------------------------------------------------------------------  
     * Alias of get()
     * ---------------------------------------------------------------------------  
     */
    public function all() {
        return $this->get();
    }


    /**
     * ---------------------------------------------------------------------------  
     * Returns the first result from a query. 
     *
     * Examples:
     *
     * Db()->table('users')->first();
     * ---------------------------------------------------------------------------  
     */
    public function first()
    {
        $this->limit(1);
        $results = call_user_func_array(array($this, 'get'), func_get_args());
        if($results)
            return array_shift($results);
        return false;
    }


    /**
     * ---------------------------------------------------------------------------  
     * Finds a single record based on an id or slug. If an int is passed its
     * assumed to be an id. Anything not an int is considered a slug.
     * ---------------------------------------------------------------------------  
     */
    public function find($idOrSlug)
    {
        $type = (is_numeric($idOrSlug)) ? 'id' : 'slug'; // Need to use is_numeric because is_int wont work with strings (ex: "12")
        return $this->where($type, '=', $idOrSlug)->first();
    }


    /**
     * ---------------------------------------------------------------------------  
     * Counts the number of records returned from the query
     *
     * Examples:
     *
     * Db()->get('users')->count(); // Count all records
     * Db()->get('users')->where('age', '>', '23')->count()
     * ---------------------------------------------------------------------------  
     */
    public function count($column = '*') {
        return $this->select(sprintf('COUNT(%s) AS count', $column))->first()->count;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function orderBy($column, $direction = 'ASC')
    {
        if(!strlen($this->_orderBy))
            $this->_orderBy = ' ORDER BY';
        else
            $this->_orderBy .= ',';

        $this->_orderBy .= sprintf(' %s %s', $column, strtoupper($direction));
        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function limit($limit, $offset = null)
    {
        if(is_null($offset))
            $this->_limit = sprintf(' LIMIT %d', $limit);
        else
            $this->_limit = sprintf(' LIMIT %d, %d', $offset, $limit);

        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * Starts a where clause. 
     *
     * Examples:
     *
     * Db::table('users')->where('name', 'LIKE', 'Bob')->get();
     * Db::table('users')->where('email', '=', 'bob@example.com')->first();
     * ---------------------------------------------------------------------------  
     */
    public function where($column, $operator, $value, $modifier = null)
    {
        if(is_null($modifier))
            $this->_where = sprintf(' WHERE %s %s ?', $column, $operator);
        else
            $this->_where .= sprintf(' %s %s %s ?', $modifier, $column, $operator);

        $this->_bindings[] = $value;
        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function andWhere($column, $operator, $value)
    {
        $this->where($column, $operator, $value, 'AND');
        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function orWhere($column, $operator, $value)
    {
        $this->where($column, $operator, $value, 'OR');
        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * Does an inner join on a table. The first param is the table to join, the
     * last 3 params are used to create the ON statement.
     *
     * Examples:
     *
     * DB::table('users')->join('photos', 'photos.user_id', '=', 'user.id')->get();
     * ---------------------------------------------------------------------------  
     */
    public function join($table, $column1, $operator, $column2, $modifier = 'INNER')
    {
        $this->_join .= sprintf(' %s JOIN %s ON %s %s %s', 
            $modifier, $table, $column1, $operator, $column2);

        return $this;
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    public function leftJoin($table, $column1, $operator, $column2)
    {
        return $this->join($table, $column1, $operator, $column2, 'LEFT');
    }


    /**
     * ---------------------------------------------------------------------------  
     * TODO
     * ---------------------------------------------------------------------------  
     */
    private function _getTableName()
    {
        $class = get_called_class();
        $bits = explode('_', $class);
        $model = sprintf('%s_%s', 
            strtolower($bits[0]), 
            str_replace('model', '', strtolower($bits[1]))
        );

        return String::plural($model);
    }


}
