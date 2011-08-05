<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Testes_Admin
 * @author Shawn Adrian <shawn@nerdburn.com>
 * @version 1.0
 * =============================================================================
 */
class Testes_Admin {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function manage() 
    {
        	View::load('Testes', 'admin/manage', array('testes' => Testes_Model::get()));
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function create() 
    {
       if($_POST)
       {
			Validate::check('content', 'Content', array('required'));
			Validate::check('author', 'Author', array('required'));

			if(Validate::passed())
			{
				$id = Testes_Model::create(
					$_POST['content'],
					$_POST['author']
				);

				if($id)
				{
						Message::store(MSG_OK, 'Testimonial successfully published.');
						Router::redirect('admin/testes');
				}
				else
					Message::set(MSG_ERR, 'Unkown error creating testimonial. Please try again.');
			}
       }
       
		$data = array();
      View::load('Testes', 'admin/create', $data);
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function edit($id) 
    {
		  $data = array(); // some key / value pair data to send to view
        View::load('Testes', 'admin/edit', $data);
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($id)
    {
    		if(Testes_Model::delete($id))
				Message::store(MSG_OK, 'Testimonial deleted successfully.');
			else
				Message::store(MSG_ERR, 'Unkown error while deleting testimonial. Please try again.');

        Router::redirect('admin/testes');
    }



}