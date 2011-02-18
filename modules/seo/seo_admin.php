<?php
class SEO_Admin {

	public static function manage()
	{
		View::load('SEO_Admin', 'seo_manage',
			array('items' => SEO_Model::get_all()));
	}

	public static function create()
	{
		if($_POST)
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

				Message::store(MSG_OK, 'Path SEO created successfully.');
				Router::redirect('admin/seo');
			}
			else
				Message::set(MSG_ERR, 'That path has already been configured.');
		}
			
		View::load('SEO_Admin', 'seo_create');
	}

	public static function edit($cid)
	{
		if($_POST)
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

		View::load('SEO_Admin', 'seo_edit', 
			array('item' => SEO_Model::get_by_cid($cid)));
	}

}
