<?php
class Feature {

	public static function get($tag) {
		return Feature_Model::get_by_tag($tag);
	}

	public static function get_all($tag) {
		return Feature_Model::get_all_by_tag($tag);
	}

	public static function get_random($tag) {
		return Feature_Model::get_random_by_tag($tag);
	}

}
