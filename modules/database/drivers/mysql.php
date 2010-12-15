<?php
/**
 * =============================================================================
 * Database
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * A MySQL driver for the Database library.
 * =============================================================================
 */
class Database {

    private static $_conn   = array();
    private static $_result = array();
    private static $_aliases = array();
    private static $_active_alias = null;
    private static $_type_map = array(
        'auto increment' => 'INT',
        'varchar' => 'VARCHAR',
        'char' => 'CHAR',
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'datetime' => 'DATETIME',
        'int' => array(
            'tiny' => 'TINYINT',
            'small' => 'SMALLINT',
            'normal' => 'INT',
            'big' => 'BIGINT',
        ),
        'text' => array(
            'tiny' => 'TINYTEXT',
            'small' => 'TINYTEXT',
            'normal' => 'TEXT',
            'big' => 'LONGTEXT'
        ),
        'blob' => array(
            'normal' => 'BLOB',
            'big' => 'LONGBLOG'
        )
    );

    /**
     * -------------------------------------------------------------------------
     * Get the number of affected rows by the last INSERT, UPDATE, REPLACE or 
     * DELETE query.
     * -------------------------------------------------------------------------
     */
    public static function affected_rows() {
        return mysql_affected_rows(self::$_conn[self::$_active_alias]);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Closes the connection to a connected databaes.
     *
     * @param $alias
     *      The alias of the connection you want to close. Defaults to the
     *      default database connection set in config.
     * -------------------------------------------------------------------------
     */
    public static function close($alias = DATABASE_ALIAS) {
        mysql_close(self::$_conn[$alias]);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Establishes a new connection to a MySQL database. The connection is
     * stored in an array of "active" connections to support multiple
     * databases in a static class.
     *
     * @param $host
     *      The database host you want to connect to.
     *
     * @param $database
     *      The database name you want to select.
     *
     * @param $user
     *      The username to connect to the database.
     *
     * @param $pass
     *      The password for $user.
     *
     * @param $alias
     *      The database connection alias. This is used to switch between 
     *      multiple connections in the same static class.
     * -------------------------------------------------------------------------
     */
    public static function connect($host = DATABASE_HOST, 
        $database = DATABASE_NAME, $user = DATABASE_USER, 
        $pass = DATABASE_PASS, $alias = DATABASE_ALIAS)
    {
        Caffeine::debug(1, 'Database', 'Connecting to alias "%s"', $alias);
        
        self::$_conn[$alias] = mysql_connect(
            DATABASE_HOST,
            DATABASE_USER,
            DATABASE_PASS
        );
        
        if(!self::$_conn[$alias])
            trigger_error('Database Connection Error: ' . mysql_error(), E_USER_ERROR);
            
        if(!mysql_select_db(DATABASE_NAME, self::$_conn[$alias]))
            trigger_error('Database Selection Error: ' . mysql_error(), E_USER_ERROR);
            
        self::$_active_alias = $alias;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Set a different active database alias. This is so the same static class
     * can support multiple database connections, associated by aliases.
     *
     * @param $alias
     *      The name of the database alias you want set as active.
     * -------------------------------------------------------------------------
     */
    public static function set_active($alias = DATABASE_ALIAS) {
        self::$_active_alias = $alias;
    }
    
    /**
     * -------------------------------------------------------------------------
     * A convenience method used to get all rows via fetch_array().
     *
     * @param @result_type
     *      The type of array to fetch. Acceptable values are MYSQL_ASSOC,
     *      MYSQL_NUM and MYSQL_BOTH. 
     * -------------------------------------------------------------------------
     */
    public static function fetch_all($result_type = MYSQL_ASSOC) 
    {
        $rows = array();
        
        while($row = self::fetch_array())
            $rows[] = $row;
            
        return $rows;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Returns an array that corresponds to the fetched row.
     *
     * @param @result_type
     *      The type of array to fetch. Acceptable values are MYSQL_ASSOC,
     *      MYSQL_NUM and MYSQL_BOTH. 
     * -------------------------------------------------------------------------
     */
    public static function fetch_array($result_type = MYSQL_ASSOC) 
    {
        return mysql_fetch_array(self::$_result[self::$_active_alias], 
            $result_type);
    }

    /**
     * -------------------------------------------------------------------------
     * Returns a single column value from a row.
     *
     * @param $col
     *      The column you want a value for.
     * -------------------------------------------------------------------------
     */
    public static function fetch_single($col) 
    {
        $row = self::fetch_array();
        return $row[$col];
    }
    
    /**
     * -------------------------------------------------------------------------
     * Retrieves the ID generated for an AUTO_INCREMENT column by the previous 
     * query (usually INSERT). 
     * -------------------------------------------------------------------------
     */
    public static function insert_id() {
        return mysql_insert_id(self::$_conn[self::$_active_alias]);
    }
    
    /**
     * -------------------------------------------------------------------------
     * An abstraction method to support limiting results across different
     * databases.
     *
     * @param $sql
     *      The sql command you want a limit appended to.
     *
     * @param $limit
     *      The maximum number of results to return.
     *
     * @param $offset
     *      The number of rows to offset before getting $limit.
     * -------------------------------------------------------------------------
     */
    public static function limit($sql, $limit, $offset = 0) {
        return sprintf('%s LIMIT %d, %d', $sql, $offset, $limit);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Retrieve the number of rows from a SELECT or SHOW query.
     * -------------------------------------------------------------------------
     */
    public static function num_rows() {
        return mysql_num_rows(self::$_result[self::$_active_alias]);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Get the number of fields from a query.
     * -------------------------------------------------------------------------
     */
    public static function num_fields() {
        return mysql_num_fields(self::$_result[self::$_active_alias]);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Gets the result from the last query.
     * -------------------------------------------------------------------------
     */
    public static function result() {
        return self::$_result[self::$_active_alias];
    }
    
    /**
     * -------------------------------------------------------------------------
     * Runs a query on the currently active database.
     *
     * @param $sql
     *      The sql query to run.
     *
     * @param $args
     *      An array of arguments to be re-written via the sprintf command. See
     *      the PHP sprintf command for syntax.
     * -------------------------------------------------------------------------
     */
    public static function query()
    {
        // Handle arguments
        $args = func_get_args();
        $sql = array_shift($args);
        
        // Check if args have been passed as array in second param
        if(isset($args[0]) && is_array($args[0]))
            $args = $args[0];
        
        // Clean arguments
        foreach($args as &$a)
        {
            $a = self::_escape($a);
            if(is_string($a))
                $a = "'".$a."'";
        }
            
        // Inject arguments
        $query = call_user_func_array('sprintf', 
            array_merge(array($sql), $args));
            
        // Replace table name with prefix, if any
        $query = preg_replace('({([\w\_]+)})', 
            sprintf('%s$1', DATABASE_PREFIX), $query);
            
        Caffeine::debug(3, 'Database', $query);
            
        $result = mysql_query($query);
        if(!$result)
            trigger_error('Query Error: ' . mysql_error(), E_USER_ERROR);
            
        self::$_result[self::$_active_alias] = $result;
        
        return $result;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Callback for Database::install event.
     * -------------------------------------------------------------------------
     */
    public static function callback_install($class, $schema = array())
    {
        Caffeine::debug(3, 'Database', 'Installing schema returned by the "%s" class', $class);
    
        $sql = '';
        
        foreach($schema as $table => $table_data)
        {
            $sql = 'CREATE TABLE IF NOT EXISTS {'. $table . '} (';
            
            foreach($table_data['fields'] as $field => $field_data)
            {
                // id int unsigned not null auto_increment
                // blog_post text not null
                
                // Add the field name
                $sql .= $field . ' ';
                
                // If type has size, get type by size
                if(is_array(self::$_type_map[$field_data['type']]))
                
                    // If size is set 
                    if(isset($field_data['size']))  
                        $sql .= self::$_type_map[$field_data['type']][$field_data['size']];
                        
                    // Else no size, use default
                    else
                        $sql .= self::$_type_map[$field_data['type']]['normal'];
                    
                // Else get regular type
                else
                    $sql .= self::$_type_map[$field_data['type']];
                    
                // If length is set, add to type
                if(isset($field_data['length']))
                    $sql .= '('. $field_data['length'] .')';
                    
                $sql .= ' ';
                
                // Check for unsigned
                if(isset($field_data['unsigned']))
                    $sql .= 'UNSIGNED ';
                    
                // Check for not null
                if(isset($field_data['not null']))
                    $sql .= 'NOT NULL ';
                                    
                // Check for default
                if(isset($field_data['default']))
                    if(is_int($field_data['default']))
                        $sql .= 'DEFAULT ' . $field_data['default'] . ' ';
                    else
                        $sql .= 'DEFAULT \'' . $field_data['default'] . '\' ';
                    
                // Check for auto increment
                if($field_data['type'] == 'auto increment')
                    $sql .= 'AUTO_INCREMENT';
                    
                $sql = trim($sql) . ',';
            }
            
            // Check for indexes
            if(isset($table_data['indexes']))
                foreach($table_data['indexes'] as $k => $v)
                    $sql .= 'INDEX '. $k .'('. implode(',', $v). '),';
            
            // Check for primary keys
            if(isset($table_data['primary key']))
                $sql .= 'PRIMARY KEY('. implode(',', $table_data['primary key']). '),';
            
            $sql = trim($sql, ',');
            $sql .= ');';
            
            self::query($sql);
        }
    }
    
    /**
     * -------------------------------------------------------------------------
     * Clean data to prevent SQL injection attacks.
     *
     * @param $value
     *      The value to clean.
     * -------------------------------------------------------------------------
     */
    private static function _escape($value)
    {
        if(get_magic_quotes_gpc())
            $value = stripslashes($value);
            
        return mysql_real_escape_string($value, 
            self::$_conn[self::$_active_alias]);
    }

}
