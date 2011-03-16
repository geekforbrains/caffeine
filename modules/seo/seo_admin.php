<?php
class SEO_Admin {

	public static function manage()
	{
		View::load('SEO', 'admin/manage',
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
					$_POST['is_default'] = isset($_POST['is_default']) ? 1 : 0;

					if(SEO_Model::create($_POST['path'], $_POST['title'], $_POST['is_default']))
					{
						Message::store(MSG_OK, 'SEO path created successfully.');
						Router::redirect('admin/seo');
					}
					else	
						Message::set(MSG_ERR, 'Error creating path. Please try again.');
				}
				else
					Message::set(MSG_ERR, 'That path has already been configured.');
			}
		}
			
		View::load('SEO', 'admin/create');
	}

	public static function edit($cid)
	{
		if(!$path = SEO_Model::get_by_cid($cid))
			Router::redirect('admin/seo');

		if($_POST)
		{
			if(isset($_POST['update_path']))
			{
				Validate::check('path', 'Path', array('required'));

				if(Validate::passed())
				{
					$_POST['is_default'] = isset($_POST['is_default']) ? 1 : 0;
					$_POST['prepend'] = isset($_POST['prepend']) ? $_POST['prepend'] : null;
					$_POST['append'] = isset($_POST['append']) ? $_POST['append'] : null;

					$status = SEO_Model::update(
						$cid,
						$_POST['path'],
						$_POST['title'],
						$_POST['prepend'],
						$_POST['append'],
						$_POST['is_default']
					);

					if($status)
						Message::set(MSG_OK, 'Path updated successfully.');
					else
						Message::set(MSG_ERR, 'Error updating path. Please try again');
				}
			}

			elseif(isset($_POST['add_meta']))
			{
				Validate::check('name', 'Name', array('required'));
				Validate::check('content', 'Content', array('required'));

				if(Validate::passed())
				{
					$_POST['is_httpequiv'] = isset($_POST['is_httpequiv']) ? 1 : 0;

					$status = SEO_Model::create_meta(
						$cid,
						$_POST['name'],
						$_POST['content'],
						$_POST['is_httpequiv']
					);

					if($status)
						Message::set(MSG_OK, 'Meta data added successfully.');
					else
						Message::set(MSG_ERR, 'Error adding meta data.');
				}
			}

			// If posting new data, get updated path
			$path = SEO_Model::get_by_cid($cid); // Get updated
		}

		View::load('SEO', 'admin/edit', array('path' => $path));
	}

	public static function delete($cid)
	{
		if(SEO_Model::delete($cid))
			Message::store(MSG_OK, 'SEO path deleted successfully.');
		else
			Message::store(MSG_ERR, 'Error deleting SEO path. Please try again.');

		Router::redirect('admin/seo');
	}

	public static function delete_meta($path_cid, $meta_cid)
	{
		SEO_Model::delete_meta($meta_cid);
		Message::store(MSG_OK, 'Meta data deleted successfully.');
		Router::redirect('admin/seo/edit/' . $path_cid);
	}

	public static function analytics()
	{
		if($_POST)
		{
			SEO_Model::update_analytics($_POST['code']);
			Message::set(MSG_OK, 'Analytics updated successfully.');
		}

		View::load('SEO', 'admin/analytics',
			array('analytics' => SEO_Model::get_analytics()));
	}

}
