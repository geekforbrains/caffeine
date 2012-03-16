<?php

class Db_Runner extends Db_Driver {

    /**
     * Stores all models found in the system.
     */
    private static $_models = null;

    /**
     * Clears the database of any tables and re-creates new tables for every 
     * model found in the system.
     */
    public static function install($force = false)
    {
        if(self::_clearDatabase($force))
        {
            if($models = self::_getModels())
                foreach($models as $model)
                    self::_createTable(new $model());
        }
    }

    /**
     * Updates any existing tables who's models have changed, and creates
     * any new tables as needed.
     */
    public static function update()
    {
        if($models = self::_getModels())
        {
            foreach($models as $model)
            {
                $instance = new $model();
                
                if($instance->exists())
                    self::_updateTable($instance);
                else
                    self::_createTable($instance);
            }
        }
    }

    /**
     * TODO Will install test seed data based on the seed.php file.
     */
    public static function seed()
    {

    }

    /**
     * Runs the optimize command on all tables in the current database. This is not
     * reflected by models, but tables that already exist.
     */
    public static function optimize()
    {
        if($results = Db::query('SHOW TABLES FROM ' . Config::get('db.name')))
            foreach($results as $table)
                $result = Db::query('OPTIMIZE TABLE ' . $table[0]);
    }

    /**
     * Returns an array of all possible models within the system.
     */
    private static function _getModels()
    {
        if(is_null(self::$_models))
        {
            self::$_models = array();
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

                            if(!in_array($class, self::$_models))
                                self::$_models[] = $class;
                        }
                    }
                }
            }
        }

        return self::$_models;
    }

    /**
     * TODO
     */
    private static function _createTable($model)
    {
        $fields = $model->_fields;

        // If belongsTo other models, create foreign key fields
        foreach($model->_belongsTo as $m)
        {
            $bits = explode('.', $m);
            $key = $bits[1] . '_id';

            $model->addIndex($key);

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

        // Only add id field if enabled and one wasn't added manually
        if($model->_createId && !isset($fields['id']))
            $fields = self::_addIdField($fields);

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

        //$sql = 'CREATE TABLE IF NOT EXISTS ' . $model->tableName . ' (';
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $model->_table . ' (';
        
        foreach($fields as $field => $fieldData)
            $sql .= self::_buildField($field, $fieldData);
        
        foreach($model->_indexes as $k)
            $sql .= 'INDEX(' . $k . '),';

        foreach($model->_fulltext as $ft)
            $sql .= sprintf('FULLTEXT(' . $ft . '),');
            //$sql .= sprintf('FULLTEXT(%s),', implode(',', $model->_fulltext));

        if(isset($fields['id']))
            $sql .= 'PRIMARY KEY(id)';

        $sql = trim($sql, ',') . ') ENGINE=' . Config::get('db.engine');
        
        Db::query($sql);

        if($model->_hasAndBelongsToMany)
            self::_createHABTM($model);
    }

    /**
     * Adds a standard id field to the given array of fields.
     *
     * This method is used in the _createTable and _updateTable methods.
     */
    private static function _addIdField($fields)
    {
        return array_merge(array(
            'id' => array(
                'type' => 'auto increment',
                'unsigned' => true,
                'not null' => true
            )),
            $fields
        );
    }

    /**
     * Creates a reference table for models with the hasAndBelongsToMany option set.
     *
     * HABTM tables are a combination of each models module and model name. The two names
     * are sorted alphabetically and then joined by an underscore and prepended with "habtm_".
     *
     * Example: blog.post + blog.comment = habtm_blogcategories_blogposts
     * Example: gallery.photo + media.file = habtm_galleryphotos_mediafiles
     *
     * This long naming structure ensures no name collisions will occur.
     */
    private static function _createHABTM($model)
    {
        foreach($model->_hasAndBelongsToMany as $refModel)
        {
            //$tbl1Bits = explode('_', $model->tableName);
            $tbl1Bits = explode('_', $model->_table);
            $tbl1Key = $tbl1Bits[0] . '_' . String::singular($tbl1Bits[1]);
            $tbl1 = implode('', $tbl1Bits);

            $tbl2Bits = explode('.', $refModel);
            $tbl2Key = $tbl2Bits[0] . '_' . $tbl2Bits[1];
            $tbl2Bits[1] = String::plural($tbl2Bits[1]);
            $tbl2 = implode('', $tbl2Bits);

            $names = array($tbl1, $tbl2);
            sort($names);
            $table = 'habtm_' . implode('_', $names);

            Db::query(sprintf(
                'CREATE TABLE IF NOT EXISTS %s (%s INT NOT NULL, %s INT NOT NULL, INDEX(%2$s), INDEX(%3$s)) ENGINE=%s',
                $table,
                $tbl1Key . '_id',
                $tbl2Key . '_id',
                Config::get('db.engine')
            ));
        }
    }
    
    /**
     * Gets the details of a models table and compares it with the current fields and options
     * of the model. If anything has changed, the table will be altered
     *
     * TODO Create belongsTo fields and indexes that are new/re-added
     */
    private static function _updateTable($model)
    {
        $rawFields = $model->describe();
        $tableFields = array();
        $belongsToFields = array();

        foreach($model->_belongsTo as $ref)
        {
            $refBits = explode('.', $ref);
            $belongsToFields[] = $refBits[1] . '_id';       
        }

        // Sort table fields into nice array format for easier look ups by id and type
        foreach($rawFields as $f)
        {
            // Ignore timestamps fields, we'll handle those seperately
            if($f->Field == 'created_at' || $f->Field == 'updated_at')
                continue;

            // Ignore any belongsTo ids (foreign keys)
            if($model->_belongsTo && in_array($f->Field, $belongsToFields))
                continue;

            $tableFields[$f->Field] = array(
                'type' => $f->Type,
                'not null' => ($f->Null == 'NO') ? true : false,
                'key' => $f->Key,
                'default' => $f->Default
            );
        }

        $modelFields = $model->_fields;

        // If no id set, but ids are enabled, manually add it
        if($model->_createId && !isset($modelFields['id']))
            $modelFields = self::_addIdField($modelFields);

        foreach($modelFields as $field => $data)
        {
            // Check for new fields, which are fields in the model but not in the table
            if(!isset($tableFields[$field]))
                Db::query(rtrim('ALTER TABLE ' . $model->_table . ' ADD ' . self::_buildField($field, $data), ','));
            
            // Check if model field is different from current able field, if it is, update
            elseif(self::_fieldIsDifferent($field, $data, $tableFields[$field]))
                Db::query(rtrim('ALTER TABLE ' . $model->_table . ' MODIFY ' . self::_buildField($field, $data), ','));
        }

        // Check for new or removed indexes
        self::_checkIndexes($model, $belongsToFields);

        // Check for fields that were removed from model, but exist in table, delete them
        // TODO Add warning with option to skip, delete or delete without prompt
        // TODO Above should work with http mode via force, or cmd line
        foreach($tableFields as $field => $data)
        {
            if(!isset($modelFields[$field]))
                Db::query('ALTER TABLE ' . $model->_table . ' DROP COLUMN ' . $field);
        }
    }

    /**
     * Loops through a field array and builds its query based on the data values such as type
     * length or size and wether its null or unsigned.
     *
     * This is used for creating tables as well as altering tables.
     */
    private static function _buildField($field, $fieldData)
    {
        $sql = $field . ' ';
        
        if(is_array(self::$_typeMap[$fieldData['type']]))
        {
            if(isset($fieldData['size']))  
                $sql .= self::$_typeMap[$fieldData['type']][$fieldData['size']];
                
            else
                $sql .= self::$_typeMap[$fieldData['type']]['normal'];
        }
        else
            $sql .= self::$_typeMap[$fieldData['type']];
            
        if(isset($fieldData['length']))
            $sql .= '('. $fieldData['length'] .')';
            
        $sql .= ' ';
        
        if(isset($fieldData['unsigned']) && $fieldData['unsigned'])
            $sql .= 'UNSIGNED ';

        if(isset($fieldData['signed']) && $fieldData['signed'])
            $sql .= 'SIGNED ';

        if(isset($fieldData['not null']) && $fieldData['not null'])
            $sql .= 'NOT NULL ';
                            
        if(isset($fieldData['default']))
        {
            if(is_int($fieldData['default']))
                $sql .= 'DEFAULT ' . $fieldData['default'] . ' ';
            else
                $sql .= 'DEFAULT \'' . $fieldData['default'] . '\' ';
        }
            
        if($fieldData['type'] == 'auto increment')
            $sql .= 'AUTO_INCREMENT';
            
        return trim($sql) . ',';
    }

    /**
     * Clears the current database of all tables and data.
     *
     * If any tables exist in the database, a warning both in CLI and HTTP mode will
     * be displayed for continuing.
     */
    private static function _clearDatabase($force = false)
    {
        $dbName = Config::get('db.name');

        if($results = Db::query('SHOW TABLES FROM ' . $dbName))
        {
            if(IS_CLI)
            {
                while(true)
                {
                    fwrite(STDOUT, "There are tables in your database that will be destroyed! Continue? [yes/no]: ");
                    $answer = trim(fgets(STDIN));

                    if($answer == 'yes')
                        break;
                    elseif($answer == 'no')
                        return false;
                    else
                        fwrite(STDOUT, "Please enter yes or no\n");
                }
            }

            elseif(!$force)
                die('There are tables that will be destroyed. To continue, run "db/install/force" form the URL.');

            foreach($results as $table)
                Db::query('DROP TABLE ' . $dbName . '.' . $table[0]);
        }

        return true;
    }

    /**
     * Checks if the given field in the model is different from the data in the table.
     *
     * This command is used in the _updateTable method to determine if this column needs
     * to be altered.
     */
    private static function _fieldIsDifferent($field, $modelData, $tableData)
    {
        $tmp = $tableData['type'];

        if(strstr($tmp, '('))
        {
            $tableData['type'] = substr($tmp, 0, strpos($tmp, '('));

            if(preg_match('/\((.*?)\)/', $tmp, $match))
            {
                if(is_numeric($match[1]))
                    $tableData['length'] = $match[1];
            }
        }

        if(strstr($tmp, 'unsigned'))
            $tableData['unsigned'] = true;

        if(isset($modelData['size']))
            $modelData['type'] = strtolower(self::$_typeMap[$modelData['type']][$modelData['size']]);

        if($modelData['type'] == 'auto increment')
            $modelData['type'] = 'int';

        /*
        print_r($tableData);
        print_r($modelData);
        echo "----------\n";
        */

        if($modelData['type'] != $tableData['type'])
            return true;

        if((isset($modelData['unsigned']) && !isset($tableData['unsigned'])) ||
            (!isset($modelData['unsigned']) && isset($tableData['unsigned'])))
                return true;

        if((isset($modelData['not null']) && !isset($tableData['not null'])) ||
            (isset($modelData['not null']) && isset($tableData['not null']) && $modelData['not null'] != $tableData['not null']))
                return true;

        if(isset($modelData['length']) && isset($tableData['length']) &&
            $modelData['length'] != $tableData['length'])
                return true;

        return false;
    }

    /**
     * Checks if a models column has an index associated with it. If one exists in the model,
     * but not in the table, a new index is added. If it was removed from the model but exists in the table
     * the index will be removed.
     */
    private static function _checkIndexes($model, $belongsToFields)
    {
        if($result = Db::query('SHOW INDEX FROM ' . $model->_table))
        { 
            $tableIndexes = array();

            foreach($result as $row)
            {
                if($row['Key_name'] == 'PRIMARY')
                    continue;
                
                $tableIndexes[$row['Key_name']] = $row['Index_type'];
            }

            // Check for indexes in model, that aren't in table
            foreach($model->_indexes as $field)
            {
                if(!isset($tableIndexes[$field]))
                    Db::query('CREATE INDEX ' . $field . ' ON ' . $model->_table . ' (' . $field . ')');
            }

            // Check for fulltext indexes in model, that aren't in table
            foreach($model->_fulltext as $field)
            {
                if(!isset($tableIndexes[$field]))
                    Db::query('ALTER TABLE ' . $model->_table . ' ADD FULLTEXT(' . $field . ')');
            }

            // Check for indexes in table, that arent in model, and drop them
            foreach($tableIndexes as $field => $indexType)
            {
                // Ignore indexes created from belongsTo
                if(in_array($field, $belongsToFields))
                    continue;

                if(($indexType == 'BTREE' && !in_array($field, $model->_indexes)) ||
                    ($indexType == 'FULLTEXT' && !in_array($field, $model->_fulltext)))
                        Db::query('ALTER TABLE ' . $model->_table . ' DROP INDEX ' . $field);
            }

            // Check for belongsTo fields that aren't in the table, and create them
            foreach($belongsToFields as $field)
            {
                if(!isset($tableIndexes[$field]))
                    Db::query('CREATE INDEX ' . $field . ' ON ' . $model->_table . ' (' . $field . ')');
            }
        }
    }

}
