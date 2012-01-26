<?php

class Db extends Module {

    /**
     * Stores the current database connection
     */
    private static $_conn = null;

    /**
     * SELECT queries return records as objects
     * INSERTS return boolean
     * UPDATE or DELETE returns number of affected rows
     */
    public static function query($sql, $bindings = array(), $getInsertId = true, $class = 'stdClass')
    {
        try
        {
            if(is_null(self::$_conn))
                self::_connect();

            $debugSql = $sql;
            foreach($bindings as $value)
                $debugSql = preg_replace('/\?/', "'".$value."'", $debugSql, 1);
            Dev::debug('db', $debugSql);

            $query = self::$_conn->prepare($sql);
            $result = $query->execute($bindings);

            if(!$result)
            {
                $info = $query->errorInfo();
                Dev::debug('db', 'ERROR: ' . $info[2]);
            }
            else
            {
                if(stripos($sql, 'SELECT') === 0 || stripos($sql, 'DESCRIBE') === 0)
                    return $query->fetchAll(PDO::FETCH_CLASS, $class);

                elseif(stripos($sql, 'UPDATE') === 0 || stripos($sql, 'DELETE') === 0 || stripos($sql, 'SHOW TABLES') === 0)
                    return $query->rowCount();

                elseif($result && $getInsertId)
                    return self::$_conn->lastInsertId();

                return $result;
            }

            return false;
        } 
        catch(Exception $e)
        {
            die($e->getMessage());
        }
    }

    /** 
     * Convenience method for creating query builder for a table.
     */
    public static function table($table) {
        return new Db_Query($table);
    }
    
    public static function install()
    {
        if(!Config::get('db.install'))
            return;
        
        Db_Installer::init();
    }

    /**
     * Creates a PDO connection based on configs.
     */
    private static function _connect()
    {
        $str = sprintf('%s:host=%s;dbname=%s',
            Config::get('db.driver'),
            Config::get('db.host'),
            Config::get('db.name')
        );

        self::$_conn = new PDO($str, 
            Config::get('db.user'),
            Config::get('db.pass')
        );
    }

}
