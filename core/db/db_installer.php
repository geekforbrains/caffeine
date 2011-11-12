<?php

class Db_Installer {

    private static $_typeMap = array(
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

    public static function init()
    {
        $paths = Load::getModulePaths();

        foreach($paths as $path)
        {
            $modules = scandir($path);

            foreach($modules as $module)
            {
                if($module{0} == '.')
                    continue;

                $modelsPath = sprintf('%s%s/models/', $path, $module);

                if(file_exists($modelsPath))
                {
                    $models = scandir($modelsPath);

                    foreach($models as $model)
                    {
                        if($model{0} == '.')
                            continue;

                        $class = sprintf('%s_%sModel', ucfirst($module), ucfirst(str_replace(EXT, '', $model)));
                        $model = new $class();
                        //$model->createTable();
                        self::install($model);
                        //call_user_func(array(ucfirst($module), $model))->createTable();
                    }
                }
            }
        }
    }

    // Check if table exists
    // If not, create new one with fields
    // If it does, check for differences
    // If different, determine which fields are new (using describe) and alter table to update it
    //public static function install($table, $fields, $indexes = array(), $fullText = array())
    public static function install($model)
    {
        if($model->exists())
            self::_updateTable($model);
        else
        {
            self::_createTable($model);

            // If _hasAndBelongsToMany, create key table
            foreach($model->_hasAndBelongsToMany as $refModel)
            {
                $modelBits = explode('_', $model->tableName);
                $refBits = explode('.', $refModel); // module.model (ex: blog.post)

                $names = array($modelBits[1], String::plural($refBits[1]));
                sort($names);

                // Need to convert names to singular for id's
                $refKey1 = String::singular($names[0]);
                $refKey2 = String::singular($names[1]);

                $table = implode('_', $names);

                Db::query(sprintf(
                    'CREATE TABLE IF NOT EXISTS %s (%s INT NOT NULL, %s INT NOT NULL, INDEX(%2$s), INDEX(%3$s)) ENGINE=%s',
                    $table,
                    $refKey1 . '_id',
                    $refKey2 . '_id',
                    Config::get('db.engine')
                ));
            }
        }
    }

    //private static function _createTable($table, $fields, $indexes, $fullText)
    private static function _createTable($model)
    {
        $fields = $model->_fields;

        // Add any _belongsTo fields
        foreach($model->_belongsTo as $m)
        {
            $bits = explode('.', $m); // module.model
            $key = $bits[1] . '_id';

            // Foreign keys should be indexed
            array_push($model->_indexes, $key);

            $fields = array_merge(array(
                $key => array(
                    'type' => 'int',
                    'size' => 'normal',
                    'unsigned' => true,
                    'not null' => true
                )),
                $fields
            );
        }

        // Add id field if it wasn't added manually in the model
        if(!isset($fields['id']))
        {
            $fields = array_merge(array(
                'id' => array(
                    'type' => 'auto increment',
                    'size' => 'normal',
                    'unsigned' => true,
                    'not null' => true
                )),
                $fields
            );
        }

        // Add timestamps fields if timestamps are enabled
        if($model->_timestamps)
        {
            $fields = array_merge($fields, array(
                'created_at' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                ),
                'updated_at' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                )
            ));
        }

        $sql = 'CREATE TABLE ' . $model->tableName . ' (';
        
        foreach($fields as $field => $fieldData)
        {
            $sql .= self::_buildField($field, $fieldData);

            /*
            // Add the field name
            $sql .= $field . ' ';
            
            // If type has size, get type by size
            if(is_array(self::$_typeMap[$fieldData['type']]))
            {
            
                // If size is set 
                if(isset($fieldData['size']))  
                    $sql .= self::$_typeMap[$fieldData['type']][$fieldData['size']];
                    
                // Else no size, use default
                else
                    $sql .= self::$_typeMap[$fieldData['type']]['normal'];
            }

            // Else get regular type
            else
            {
                $sql .= self::$_typeMap[$fieldData['type']];
            }
                
            // If length is set, add to type
            if(isset($fieldData['length']))
                $sql .= '('. $fieldData['length'] .')';
                
            $sql .= ' ';
            
            // Check for unsigned
            if(isset($fieldData['unsigned']))
                $sql .= 'UNSIGNED ';
                
            // Check for not null
            if(isset($fieldData['not null']))
                $sql .= 'NOT NULL ';
                                
            // Check for default
            if(isset($fieldData['default']))
                if(is_int($fieldData['default']))
                    $sql .= 'DEFAULT ' . $fieldData['default'] . ' ';
                else
                    $sql .= 'DEFAULT \'' . $fieldData['default'] . '\' ';
                
            // Check for auto increment
            if($fieldData['type'] == 'auto increment')
                $sql .= 'AUTO_INCREMENT';
                
            $sql = trim($sql) . ',';
            */
        }
        
        /*
        // Check for indexes
        if(isset($table_data['indexes']))
            foreach($table_data['indexes'] as $k => $v)
                $sql .= 'INDEX '. $k .'('. implode(',', $v). '),';
        
        // Check for primary keys
        if(isset($table_data['primary key']))
            $sql .= 'PRIMARY KEY('. implode(',', $table_data['primary key']). '),';
        */

        // Add indexes, if any
        foreach($model->_indexes as $k)
            $sql .= 'INDEX(' . $k . '),';

        // Add fulltext, if any
        if($model->_fulltext)
            $sql .= sprintf('FULLTEXT(%s),', implode(',', $model->_fulltext));

        $sql .= 'PRIMARY KEY(id)';
        $sql = trim($sql, ',') . ') ENGINE=' . Config::get('db.engine');
        
        Db::query($sql);
    }

    /**
     * If a table exists, this method looks for any differences. If some differences
     * are found, the table is updated to reflect the new model fields.
     *
     * Check 1:
     * If some fields are found that dont exist in the table, they are added.
     *
     * Check 2:
     * Check that all fields match the types of model fields. If they are different
     * modify the tables field to match.
     *
     * Check 3:
     * If the table has too many fields, drop any fields that are un-needed.
     */
    //private static function _updateTable($table, $fields, $indexes, $fullText)
    private static function _updateTable($model)
    {
        //$_fields = Db::table($table)->describe();
        $fields = $model->_fields;
        $_fields = $model->describe();

        // Add timestamps fields if timestamps are enabled
        if($model->_timestamps)
        {
            $fields = array_merge($fields, array(
                'created_at' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                ),
                'updated_at' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                )
            ));
        }

        $newFields = array();
        $currentFields = array();

        // Put each set of fields into simple array for difference checking
        foreach($fields as $field => $data)
            $newFields[] = $field;

        foreach($_fields as $field)
            if($field->Field != 'id' && !strstr($field->Field, '_id')) // Ignore id fields
                $currentFields[] = $field->Field;

        // Check for new fields
        $diff = array_diff($newFields, $currentFields);
        if($diff)
        {
            $sql = 'ALTER TABLE ' . $model->tableName . ' ADD(';

            foreach($diff as $field)
            {
                $fieldData = $fields[$field];
                $sql .= self::_buildField($field, $fieldData);
            }

            $sql = rtrim($sql, ',') . ');';

            Db::query($sql);
        }

        // Check of extra fields that need to be dropped
        $diff = array_diff($currentFields, $newFields);
        foreach($diff as $field)
            Db::query('ALTER TABLE ' . $model->tableName . ' DROP COLUMN ' . $field . ';');

        // Run modify for all new fields regardless if they are different. This way its always up to date.
        foreach($fields as $field => $fieldData)
            Db::query('ALTER TABLE ' . $model->tableName . ' MODIFY ' . rtrim(self::_buildField($field, $fieldData), ',') . ';');
    }

    /**
     * Loops through a field array and builds its query based on the data values such as type
     * length or size and wether its null or unsigned.
     *
     * This is used for creating tables as well as altering tables.
     */
    private static function _buildField($field, $fieldData)
    {
        // Add the field name
        $sql = $field . ' ';
        
        // If type has size, get type by size
        if(is_array(self::$_typeMap[$fieldData['type']]))
        {
        
            // If size is set 
            if(isset($fieldData['size']))  
                $sql .= self::$_typeMap[$fieldData['type']][$fieldData['size']];
                
            // Else no size, use default
            else
                $sql .= self::$_typeMap[$fieldData['type']]['normal'];
        }

        // Else get regular type
        else
        {
            $sql .= self::$_typeMap[$fieldData['type']];
        }
            
        // If length is set, add to type
        if(isset($fieldData['length']))
            $sql .= '('. $fieldData['length'] .')';
            
        $sql .= ' ';
        
        // Check for unsigned
        if(isset($fieldData['unsigned']))
            $sql .= 'UNSIGNED ';
            
        // Check for not null
        if(isset($fieldData['not null']))
            $sql .= 'NOT NULL ';
                            
        // Check for default
        if(isset($fieldData['default']))
            if(is_int($fieldData['default']))
                $sql .= 'DEFAULT ' . $fieldData['default'] . ' ';
            else
                $sql .= 'DEFAULT \'' . $fieldData['default'] . '\' ';
            
        // Check for auto increment
        if($fieldData['type'] == 'auto increment')
            $sql .= 'AUTO_INCREMENT';
            
        return trim($sql) . ',';
    }

}
