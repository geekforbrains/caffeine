<?php
class Media {

	public static function dialog($type) {
		View::load('Media', sprintf('media_%s_dialog', $type));
	}

}
