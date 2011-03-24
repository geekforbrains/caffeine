<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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
        'varchar' => 'VARCHAR',
        'char' => 'CHAR',
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'datetime' => 'DATETIME',
        'auto increment' => array(
            'tiny' => 'TINYINT',
            'small' => 'SMALLINT',
            'normal' => 'INT',
            'big' => 'BIGINT'
		),
        'int' => array(
            'tiny' => 'TINYINT',
            'small' => 'SMALLINT',
            'normal' => 'INT',
            'big' => 'BIGINT'
        ),
        'text' => array(
            'tiny' => 'TINYTEXT',
            'small' => 'TINYTEXT',
            'normal' => 'TEXT',
            'big' => 'LONGTEXT'
        ),
        'blob' => array(
			'tiny' => 'TINYBLOB',
			'small' => 'TINYBLOB',
            'normal' => 'BLOB',
			'medium' => 'MEDIUMBLOB',
            'big' => 'LONGBLOB'
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

		Debug::log('Database', 'Connection to alias "%s"', $alias);
        
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
            
        // Replace table name with prefix, if any
        $query = preg_replace('({([\w\_]+)})', 
            sprintf('%s$1', DATABASE_PREFIX), $sql);

        // Inject arguments
        $query = call_user_func_array('sprintf', 
            array_merge(array($query), $args));
            
        Debug::log('Database', $query);
            
        $result = mysql_query($query);
        if(!$result)
            trigger_error('Query Error: ' . mysql_error(), E_USER_ERROR);
            
        self::$_result[self::$_active_alias] = $result;
        
        return $result;
    }

	/**
	 * -------------------------------------------------------------------------
	 * A short-hand method for running simple insert queries.
	 *
	 * @param $table
	 * 		The name of the table to run the insert on.
	 *
	 * @param $data
	 *		An array of key value pairs to be inserted. The key is the column
	 *		name in the table, and the value is the value to be inserted into
	 *		the key column.
	 *
	 * @return boolean
	 *		Returns true if the insert was successful. False otherwise.
	 *
	 * TODO: This code needs refactoring. Its a little sloppy.
	 * -------------------------------------------------------------------------
	 */
	public static function insert($table, $data)
	{
		$keys = array();
		$vals = array(); 
		$tmps = array();

		foreach($data as $key => $val)
		{
			$keys[] = $key;
			$vals[] = $val;
			$tmps[] = '%s'; // Used to pass the number of values to the query
		}

		$query = sprintf('INSERT INTO {%s} (%s) VALUES (%s)',
			$table,
			implode(',', $keys),
			implode(',', $tmps)
		);

		array_unshift($vals, $query);
		call_user_func_array(array('Database', 'query'), $vals);

		if(self::affected_rows() > 0)
			return true;
		return false;
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function update($table, $data, $where = null)
	{
		// Stores replacement values
		$reps = array();

		// Build set values
		$sets = '';
		foreach($data as $k => $v)
		{
			$sets .= $k .' = %s, ';
			$reps[] = $v;
		}
		$sets = rtrim($sets, ', ');

		// Build where values
		$wheres = '';
		if($where)
		{
			$wheres = ' WHERE ';
			foreach($where as $k => $v)
			{
				$wheres .= $k .' = %s AND ';
				$reps[] = $v;
			}
			$wheres = rtrim($wheres, 'AND ');
		}

		self::query(
			'UPDATE {' .$table. '} SET ' .$sets . $wheres,
			$reps
		);

		if(self::affected_rows() > 0)
			return true;
		return false;
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function delete($table, $where = null) 
	{
		$reps = array();

		$wheres = '';
		if($where)
		{
			$wheres = 'WHERE ';
			foreach($where as $k => $v)
			{
				$wheres .= $k . ' = %s AND ';
				$reps[] = $v;
			}
			$wheres = rtrim($wheres, 'AND ');
		}

		if($where)
		{
			self::query(
				'DELETE FROM {' . $table . '} ' . $wheres,
				$reps
			);
		}
		else
			self::query('TRUNCATE {' . $table . '}');

		if(self::affected_rows() > 0)
			return true;
		return false;
	}

	// TODO
	public static function exists($table, $field, $value)
	{
		self::query('
			SELECT 
				'.$table.'.'.$field.'
			FROM '.$table.' 
				JOIN content ON content.id = '.$table.'.cid
			WHERE '.$table.'.'.$field.' LIKE %s
				AND content.site_cid = %s
			', 
			$value,
			User::current_site()
		);
		
		if(self::num_rows() > 0)
			return true;
		return false;
	}

	public static function get_all($table)
	{
		self::query('
			SELECT
				'.$table.'.*,
				content.type AS content_type,
				content.site_cid,
				content.user_cid,
				content.created,
				content.updated
			FROM '.$table.'
				JOIN content ON content.id = '.$table.'.cid
			WHERE
				content.site_cid = %s
			',
			User::current_site()
		);

		return self::fetch_all();
	}

	public static function get_by_cid($table, $cid)
	{
		self::query('
			SELECT
				'.$table.'.*,
				content.type AS content_type,
				content.site_cid,
				content.user_cid,
				content.created,
				content.updated
			FROM '.$table.'
				JOIN content ON content.id = '.$table.'.cid
			WHERE '.$table.'.cid = %s
				AND content.site_cid = %s
			',
			$cid,
			User::current_site()
		);

		if(self::num_rows() > 0)
			return self::fetch_array();
		return false;
	}
    
    /**
     * -------------------------------------------------------------------------
     * Callback for Database::install event.
     * -------------------------------------------------------------------------
     */
    public static function callback_install($class, $schema = array())
    {
        Debug::log('Database', 'Installing schema returned by the "%s" class', $class);
    
        $sql = '';
        
        foreach($schema as $table => $table_data)
        {
            $sql = 'CREATE TABLE IF NOT EXISTS {'. $table . '} (';
            
            foreach($table_data['fields'] as $field => $field_data)
            {
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
