<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Testes
 * @author Shawn Adrian <shawn@nerdburn.com>
 * @version 1.0
 *
 * A module for managing testimonials.
 * =============================================================================
 */
class Testes {

	// method for displaying front-end testimonials page
	public static function testimonials()
	{
      View::load('Testimonials', 'testes', array('testes' => Testes_Model::get()));
	}

}
