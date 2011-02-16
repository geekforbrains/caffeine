<?php
final class Media_Events {
	
	public static function view_block_paths() {
		return array('Media' => CAFFEINE_MODULES_PATH . 'media/blocks/');
	}

	public static function path_callbacks()
	{
		return array(
			'admin/media/dialog/%s' => array(
				'title' => 'Media Dialog',
				'callback' => array('Media', 'dialog'),
				'auth' => true,
				'visible' => false
			)
		);
	}

}
