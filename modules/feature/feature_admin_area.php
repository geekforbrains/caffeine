<?php
class Feature_Admin_Area {

	public static function manage()
	{
		$areas = Feature_Model_Area::get_all();
		View::load('Feature', 'admin/area/manage',
			array('areas' => $areas));
	}

	public static function create()
	{
		if($_POST)
		{
			Validate::check('name', 'Name', array('required'));
			Validate::check('tag', 'Tag', array('required'));

			if(Validate::passed())
			{
				if(!Feature_Model_Area::exists($_POST['tag']))
				{
					$post = $_POST;
					$post['has_title'] = isset($post['has_title']) ? 1 : 0;
					$post['has_body'] = isset($post['has_body']) ? 1 : 0;
					$post['has_link'] = isset($post['has_link']) ? 1 : 0;
					$post['has_image'] = isset($post['has_image']) ? 1 : 0;
					$post['multiple_features'] = isset($post['multiple_features']) ? 1 : 0;
					$post['multiple_images'] = isset($post['multiple_images']) ? 1 : 0;

					if(Feature_Model_Area::create($post))
					{
						Message::store(MSG_OK, 'Area created successfully.');
						Router::redirect('admin/feature/area');
					}
					else
						Message::set(MSG_ERR, 'Error creating area. Please try again.');
				}
				else
					Message::set(MSG_ERR, 'An area with that tag already exists.');
			}
		}

		View::load('Feature', 'admin/area/create');
	}

	public static function edit($cid)
	{
		if(!$area = Feature_Model_Area::get_by_cid($cid))
			Router::redirect('admin/feature/area');

		if($_POST)
		{
			Validate::check('name', 'Name', array('required'));
			Validate::check('tag', 'Tag', array('required'));

			if(Validate::passed())
			{
				if($area['tag'] != $_POST['tag'] && Feature_Model_Area::exists($_POST['tag']))
					Message::set(MSG_ERR, 'An area with that tag already exists.');
				else
				{
					$post = $_POST;
					$post['has_title'] = isset($post['has_title']) ? 1 : 0;
					$post['has_body'] = isset($post['has_body']) ? 1 : 0;
					$post['has_link'] = isset($post['has_link']) ? 1 : 0;
					$post['has_image'] = isset($post['has_image']) ? 1 : 0;
					$post['multiple_features'] = isset($post['multiple_features']) ? 1 : 0;
					$post['multiple_images'] = isset($post['multiple_images']) ? 1 : 0;

					if(Feature_Model_Area::update($cid, $post))
					{
						Message::store(MSG_OK, 'Area updated successfully.');
						Router::redirect('admin/feature/area');
					}
					else
						Message::set(MSG_ERR, 'Error updating area. Please try again.');
				}
			}
		}

		View::load('Feature', 'admin/area/edit',
			array('area' => $area));
	}

	public static function delete($cid)
	{
		if(Feature_Model_Area::delete($cid))
			Message::store(MSG_OK, 'Area deleted successfully.');
		else
			Message::store(MSG_ERR, 'Error deleting area. Please try again.');

		Router::redirect('admin/feature/area');
	}

}
