<?php
class Feature_Admin {

	// Get a list of supported areas
	public static function manage()
	{
		$areas = Feature_Model_Area::get_all();
		View::load('Feature', 'admin/feature/manage',
			array('areas' => $areas));
	}

	// If single, check for the first feature with the given area id
	// If multiple, get all features with the given id
	public static function edit($area_cid, $feature_cid = null)
	{
		// Make sure area is legit
		if(!$area = Feature_Model_Area::get_by_cid($area_cid))
			Router::redirect('admin/feature');

		// If not feature cid set, get first feature for area
		if(is_null($feature_cid))
		{
			$feature = Feature_Model::get_by_area_cid($area_cid);
			$feature_cid = $feature['cid'];
		}

		// Handle creations and updates
		if($_POST)
		{
			if(isset($_POST['create']))
				self::_create($area_cid, $area);

			elseif(isset($_POST['update']))
				self::_update($area, $feature_cid);
		}

		$feature = ($area['multiple_features'] == 0) ? Feature_Model::get_by_cid($feature_cid) : null;
		$features = Feature_Model::get_all_by_area_cid($area_cid);

		View::load('Feature', 'admin/feature/edit',
			array(
				'area' => $area,
				'feature' => $feature,
				'features' => $features
			)
		);
	}

	public static function delete_image($area_cid, $feature_cid, $media_cid)
	{
		Feature_Model::delete_image($feature_cid, $media_cid);
		Message::store(MSG_OK, 'Image deleted successfully.');
		Router::redirect('admin/feature/edit/'.$area_cid.'/'.$feature_cid);
	}

    public static function delete($area_cid, $feature_cid)
    {
        if(Feature_Model::delete($feature_cid))
            Message::store(MSG_OK, 'Feature deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting feature. Please try again.');

        Router::redirect('admin/feature/edit/' . $area_cid);
    }

	private static function _create($area_cid, $area)
	{
		$status = true;
		$media_cid = null;

		// Check for image upload, if this area specifies it
		if($area['has_image'] > 0)
		{
			if(!$media_cid = Media::add('image'))
			{
				$status = false;
				Message::set(MSG_ERR, Media::error());
			}
		}

		// Validate required fields specified by area
		// Only continue if image is set and didnt fail
		if($status)
		{
			if($area['has_title'] > 0)
				Validate::check('title', 'Title', array('required'));

			if($area['has_body'] > 0)
				Validate::check('body', 'Body', array('required'));

			if($area['has_link'] > 0)
				Validate::check('link', 'Link', array('required'));

			if(Validate::passed())
			{
				if($feature_cid = Feature_Model::create($area['cid'], $_POST))
				{
					if($area['has_image'])
						Feature_Model::add_image($feature_cid, $media_cid);
				}
				else
				{
					Message::set(MSG_ERR, 'Error creating feature. Please try again.');
					$status = false;
				}
			}
			else
				$status = false; // Validation sets its own error message
		}

		if($status)
		{
			Message::store(MSG_OK, 'Feature created successfully.');
			Router::redirect('admin/feature/edit/' . $area_cid);
		}

		// If anything went wrong, and an image was uploaded, delete it
		elseif($media_cid)
			Media::delete($media_cid);
	}
	
	private static function _update($area, $feature_cid)
	{
		$status = true;
		$media_cid = null;
		$feature = Feature_Model::get_by_cid($feature_cid);

		// Check for image upload, if this area specifies it
		if($area['has_image'] > 0 && $_FILES['image']['size'] > 0)
		{
			if(!$media_cid = Media::add('image'))
			{
				$status = false;
				Message::set(MSG_ERR, Media::error());
			}
		}

		// Validate required fields specified by area
		// Only continue if image is set and didnt fail
		if($status)
		{
			if($area['has_title'] > 0)
				Validate::check('title', 'Title', array('required'));

			if($area['has_body'] > 0)
				Validate::check('body', 'Body', array('required'));

			if($area['has_link'] > 0)
				Validate::check('link', 'Link', array('required'));

			if(Validate::passed())
			{
				if(Feature_Model::update($feature_cid, $_POST))
				{
					// If multiple images allowed, add new on
					if($media_cid && $area['multiple_images'])
						Feature_Model::add_image($feature_cid, $media_cid);

					// If only one image allowed, delete old one and add new one
					elseif($media_cid)
						Feature_Model::update_image($feature_cid, $media_cid);
				}
				else
				{
					Message::set(MSG_ERR, 'Error updating feature. Please try again.');
					$status = false;
				}
			}
			else
				$status = false; // Validation sets its own error message
		}

		if($status)
			Message::set(MSG_OK, 'Feature updated successfully.');

		// If anything went wrong, and an image was uploaded, delete it
		elseif($media_cid)
			Media::delete($media_cid);
	}

}
