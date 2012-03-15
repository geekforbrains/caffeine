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
        self::_clearDatabase($force);

        if($models = self::_getModels())
            foreach($models as $model)
                self::_createTable(new $model());
    }

    /**
     * Updates any existing tables who's models have changed, and creates
     * any new tables as needed.
     */
    public static function update()
    {
        // Loop through models
        // If model has table, check for updates
        // If model doesn't have table, create it
    }

    /**
     * TODO Will install test seed data based on the seed.php file.
     */
    public static function seed()
    {

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
            $sql .= self::_buildField($field, $fieldData);
        
        foreach($model->_indexes as $k)
            $sql .= 'INDEX(' . $k . '),';

        if($model->_fulltext)
            $sql .= sprintf('FULLTEXT(%s),', implode(',', $model->_fulltext));

        if(isset($fields['id']))
            $sql .= 'PRIMARY KEY(id)';

        $sql = trim($sql, ',') . ') ENGINE=' . Config::get('db.engine');
        
        Db::query($sql);

        if($model->_hasAndBelongsToMany)
            self::_createHABTM($model);
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
            $tbl1Bits = explode('_', $model->tableName);
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
                        return;
                    else
                        fwrite(STDOUT, "Please enter yes or no\n");
                }
            }

            elseif(!$force)
                die('There are tables that will be destroyed. To continue, run "db/install/force" form the URL.');

            foreach($results as $table)
                Db::query('DROP TABLE ' . $dbName . '.' . $table[0]);
        }
    }

}
