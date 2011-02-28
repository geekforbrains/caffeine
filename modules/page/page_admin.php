<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Page_Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Page_Admin {

	/**
	 * -------------------------------------------------------------------------
	 * Displays an Admin page of current pages.
	 * -------------------------------------------------------------------------
	 */
	public static function manage() 
	{
		MultiArray::load(Page_Model::get_all(1));
		$published = MultiArray::indent();

		$drafts = Page_Model::get_all(0); // Drafts should not be indented
		
		View::load('Page', 'admin/manage',
			array(
				'published' => $published,
				'drafts' => $drafts
			)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Displays the Admin create page form, and handles the form submission.
	 * -------------------------------------------------------------------------
	 */
	public static function create() 
	{
		if($_POST)
		{
			Validate::check('title', 'Title', array('required'));

			if(Validate::passed())
			{
				$published = isset($_POST['publish']) ? 1 : 0;
				$visible = 1;

				$status = Page_Model::add(
					$_POST['parent_cid'],
					$_POST['title'],
					String::tagify($_POST['title']),
					$_POST['content'],
					$published,
					$visible
				);

				if($status)
				{
					Message::store(MSG_OK, 'Page created successfully.');
					Router::redirect('admin/page/manage');
				}
				else
					Message::set(MSG_ERR, 'Error creating page. Please try again.');
			}
		}

		MultiArray::load(Page_Model::get_all(1));
		$pages = MultiArray::indent();

		View::load('Page', 'admin/create',
			array('pages' => $pages));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Displays the Admin edit page form, and handles the form submission.
	 *
	 * @param $page_id
	 *		The ID of the page to edit.
	 * -------------------------------------------------------------------------
	 */
	public static function edit($cid) 
	{
		if(!Page_Model::get_by_cid($cid))
			Router::redirect('admin/page');

		if($_POST)
		{
			Validate::check('title', 'Title', array('required'));
			Validate::check('slug', 'Slug', array('required'));

			if(Validate::passed())
			{
				if(isset($_POST['delete']))
				{
					self::delete($cid);
					return;
				}

				if($_POST['parent_cid'] == $cid)
					Message::set(MSG_ERR, 'Uhh, why are you trying to set the pages parent as it\'s self?');
				else
				{
					$published = isset($_POST['published']) ? 1 : 0;
					$visible = isset($_POST['visible']) ? 1 : 0;

					Page_Model::update(
						$cid,
						$_POST['parent_cid'],
						$_POST['title'],
						$_POST['slug'],
						$_POST['content'],
						$_POST['weight'],
						$published,
						$visible
					);

					Message::store(MSG_OK, 'Page updated successfully.');
					Router::redirect('admin/page/manage');
				}
			}
		}

		MultiArray::load(Page_Model::get_all(1));
		$pages = MultiArray::indent();

		View::load('Page', 'admin/edit',
			array(
				'page' => Page_Model::get_by_cid($cid),
				'pages' => $pages
			)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Handles deleting a page. Redirect to the manage page with message.
	 *
	 * @param $page_id
	 *		The ID of the page to delete.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($cid) 
	{
		if(Page_Model::delete($cid))
			Message::store(MSG_OK, 'Page deleted successfully.');
		else
			Message::store(MSG_ERR, 'Error while deleting page. Please try again.');

		Router::redirect('admin/page');
	}

}
