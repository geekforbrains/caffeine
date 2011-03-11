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
		if(!$area = Feature_Model_Area::get_by_cid($area_cid))
			Router::redirect('admin/feature');

		$feature = null;
		$features = array();

		if(!$area['multiple_features'] && is_null($feature_cid))
			$feature = Feature_Model::get_by_area_cid($area_cid);
		
		elseif($area['multiple_features'])
		{
			if(!is_null($feature_cid))
				$feature = Feature_Model::get_by_cid($feature_cid);
			$features = Feature_Model::get_all_by_area_cid($area_cid);
		}

		View::load('Feature', 'admin/feature/edit',
			array(
				'area' => $area,
				'feature' => $feature,
				'features' => $features
			)
		);
	}

}
