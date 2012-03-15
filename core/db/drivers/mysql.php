<?php

class Db_Driver {

    protected static $_typeMap = array(
        'varchar' => 'VARCHAR',
        'char' => 'CHAR',
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'datetime' => 'DATETIME',
        'date' => 'DATE',
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

}
