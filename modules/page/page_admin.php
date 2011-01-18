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
		View::load('Page_Admin', 'page_admin_manage',
			array('pages' => Page_Model::get_all()));
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
			$user = User::get_current();
			$published = isset($_POST['publish']) ? 1 : 0;

			$status = Page_Model::add(
				$_POST['parent_cid'],
				$_POST['title'],
				String::tagify($_POST['title']),
				$_POST['content'],
				$published
			);

			if($status)
			{
				Message::store(MSG_OK, 'Page created successfully.');
				Router::redirect('admin/page/manage');
			}
			else
				Message::set(MSG_ERR, 'Error creating page. Please try again.');
		}

		View::load('Page_Admin', 'page_admin_create',
			array('pages' => Page_Model::get_all()));
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
		if($_POST)
		{
			if(isset($_POST['delete']))
			{
				self::delete($cid);
				return;
			}
				
			$published = isset($_POST['published']) ? 1 : 0;

			Page_Model::update(
				$cid,
				$_POST['parent_cid'],
				$_POST['title'],
				String::tagify($_POST['title']),
				$_POST['content'],
				$published
			);

			Message::store(MSG_OK, 'Page updated successfully.');
			Router::redirect('admin/page/manage');
		}

		View::load('Page_Admin', 'page_admin_edit',
			array(
				'page' => Page_Model::get_by_cid($cid),
				'pages' => Page_Model::get_all()
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
			Message::store(MSG_ERR, 'Error deleting page. Please try again.');

		Router::redirect('admin/page/manage');
	}

}
