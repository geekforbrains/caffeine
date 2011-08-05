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
    public static function create($content, $author)
    {

		$success = Database::insert('testes', array(
			'content' => $content,
			'author' => $author,
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
    public static function update($cid, $title, $content, $slug, $published)
    {
		Content::update($cid);

		Database::update('blog_posts',
			array(
				'title' => $title,
				'content' => $content,
				'slug' => $slug,
				'published' => $published
			),
			array('cid' => $cid)
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