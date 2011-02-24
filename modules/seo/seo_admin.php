<?php
class SEO_Admin {

	public static function manage()
	{
		View::load('SEO_Admin', 'seo_admin_manage',
			array('items' => SEO_Model::get_all()));
	}

	public static function create()
	{
		if($_POST)
		{
			Validate::check('path', 'Path', array('required'));

			if(Validate::passed())
			{
				if(!SEO_Model::exists($_POST['path']))
				{
					SEO_Model::create(
						$_POST['path'],
						$_POST['title'],
						$_POST['meta_author'],
						$_POST['meta_description'],
						$_POST['meta_keywords'],
						$_POST['meta_robots']
					);

					Message::store(MSG_OK, 'SEO path created successfully.');
					Router::redirect('admin/seo');
				}
				else
					Message::set(MSG_ERR, 'That path has already been configured.');
			}
		}
			
		View::load('SEO_Admin', 'seo_admin_create');
	}

	public static function edit($cid)
	{
		if(!SEO_Model::get_by_cid($cid))
			Router::redirect('admin/seo/manage');

		if($_POST)
		{
			Validate::check('path', 'Path', array('required'));

			if(Validate::passed())
			{
				SEO_Model::update(
					$cid,
					$_POST['path'],
					$_POST['title'],
					$_POST['meta_author'],
					$_POST['meta_description'],
					$_POST['meta_keywords'],
					$_POST['meta_robots']
				);

				Message::set(MSG_OK, 'Path SEO updated successully.');
			}
		}

		View::load('SEO_Admin', 'seo_admin_edit', 
			array('item' => SEO_Model::get_by_cid($cid)));
	}

	public static function delete($cid)
	{
		if(SEO_Model::delete($cid))
			Message::store(MSG_OK, 'SEO path deleted successfully.');
		else
			Message::store(MSG_ERR, 'Error deleting SEO path. Please try again.');

		Router::redirect('admin/seo');
	}

	public static function analytics()
	{
		if($_POST)
		{
			SEO_Model::update_analytics($_POST['code']);
			Message::set(MSG_OK, 'Analytics updated successfully.');
		}

		View::load('SEO_Admin', 'seo_admin_analytics',
			array('analytics' => SEO_Model::get_analytics()));
	}

}
