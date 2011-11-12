<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Testes_Events
 * @author Shawn Adrian <shawn@nerdburn.com>
 * @version 1.0
 * =============================================================================
 */
final class Testes_Events {
	
	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
    public static function path_callbacks()
    {  
        return array(
            
           // Front
           'testimonials' => array(
					'title' => 'Testimonials',
               'callback' => array('Testes', 'testimonials')
           ),
            // Admin
            'admin/testes' => array(
					'title' => 'Testimonials',
					'alias' => 'admin/testes/manage' // makes alias for next entry
            ),
         	'admin/testes/manage' => array( // actual URL
					'title' => 'Manage',
					'callback' => array('Testes_Admin', 'manage'),
					'auth' => 'manage testes'
         	),
            'admin/testes/create' => array(
               'title' => 'Create',
               'callback' => array('Testes_Admin', 'create'),
					'auth' => 'create testes'
            ),
            'admin/testes/edit/%d' => array(
					'title' => 'Edit Testimonial',
               'callback' => array('Testes_Admin', 'edit'),
					'auth' => 'edit testes'
            ),
            'admin/testes/delete/%d' => array(
               'callback' => array('Testes_Admin', 'delete'),
					'auth' => 'delete testes'
            )
		 );

    }	

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
    public static function database_install()
    {
        return array(
	
            'testes' => array(
                'fields' => array(
                		'id' => array(
                    		'type' => 'auto increment',
                    		'not null' => true
                		),	
                  	'author' => array(
                      	'type' => 'varchar',
                      	'length' => 255,
                      	'not null' => true
                  	),
               	'title' => array(
                   	'type' => 'varchar',
                   	'length' => 255,
                   	'not null' => true
               	),
                    'content' => array(
                        'type' => 'text',
                        'size' => 'big',
                        'not null' => true
                    ),
						 'created' => array(
							'type' => 'int',
							'size' => 'big',
							'unsigned' => true,
							'not null' => true
						)
                ), // end fields

              'indexes' => array(),
              'primary key' => array('id')

            ) // end testes table

        ); // end array of tables
    }

}
