<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Testes_Model
 * @author Shawn Adrian <shawn@nerdburn.com>
 * @version 1.0
 * =============================================================================
 */
class Testes_Model {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get($limit = 0)
    {
		// apply limit 
		($limit)? $limit = ' LIMIT '.$limit : $limit = '';

		Database::query('
			SELECT 
				*
			FROM {testes}
			ORDER BY created DESC'.
			$limit,
			User::current_site()
		);

		return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_id($id)
    {
        Database::query('SELECT * FROM {testes} WHERE id = %s', $id);
        return Database::fetch_array();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_page_cid($cid)
    {
        	Database::query('SELECT * FROM {testes} WHERE page_cid = %s', $cid);
			return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_random($limit = 0)
    {
		// apply limit 
		($limit)? $limit = ' LIMIT '.$limit : $limit = '';

		Database::query('
			SELECT 
				*
			FROM {testes}
			ORDER BY RAND()'.
			$limit,
			User::current_site()
		);

		return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function create($content, $author, $title, $page_cid)
    {

		$success = Database::insert('testes', array(
			'content' => $content,
			'author' => $author,
			'title' => $title,
			'page_cid' => $page_cid, 
			'created' => time()
		));

		if($success)
			return Database::insert_id();
			
		return false;
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function update($id, $content, $author, $title, $page_cid)
    {
        return Database::update('testes',
            array(
                'content' => $content,
                'author' => $author,
					 'title' => $title,
					 'page_cid' => $page_cid
            ),
            array('id' => $id)
        );
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($id)
    {
		Database::delete('testes', array('id' => $id));
		return true;
    }

}
