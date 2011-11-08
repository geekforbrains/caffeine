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
        if($_POST)
        {
            Validate::check('content', 'Content', array('required'));
            Validate::check('author', 'Author', array('required'));

            if(Validate::passed())
            {
                if(Testes_Model::update($id, $_POST['content'], $_POST['author']))
                {
                    Message::store(MSG_OK, 'Testimonial updated successfully.');
                    Router::redirect('admin/testes');
                }
                else
                    Message::set(MSG_ERR, 'Error updating testimonial. Pleae try again.');
            }
        }

        View::load('Testes', 'admin/edit', array(
            'teste' => Testes_Model::get_by_id($id)
        ));
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
