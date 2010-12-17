<?php
/**
 * =============================================================================
 * Page_Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Page_Admin extends Page_Model {

	/**
	 * -------------------------------------------------------------------------
	 * Displays an Admin page of current pages.
	 * -------------------------------------------------------------------------
	 */
	public static function manage() 
	{
		View::load('Page_Admin', 'page_admin_manage',
			array('pages' => self::get_all()));
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

			$status = self::add(
				$_POST['parent_cid'],
				$_POST['title'],
				String::tagify($_POST['title']),
				$_POST['content']
			);

			if($status)
			{
				Message::store('success', 'Page created successfully.');
				Router::redirect('admin/page/manage');
			}
			else
				Message::set('error', 'Error creating page. Please try again.');
		}

		View::load('Page_Admin', 'page_admin_create',
			array('pages' => self::get_all()));
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
			$status = self::update(
				$cid,
				$_POST['parent_cid'],
				$_POST['title'],
				String::tagify($_POST['title']),
				$_POST['content']
			);

			if($status)
			{
				Message::store('success', 'Page updated successfully.');
				Router::redirect('admin/page/manage');
			}
			else
				Message::set('error', 'Error updating page. Please try again.');
		}

		View::load('Page_Admin', 'page_admin_edit',
			array(
				'page' => self::get_by_cid($cid),
				'pages' => self::get_all()
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
		if(self::del($cid))
			Message::store('success', 'Page deleted successfully.');
		else
			Message::store('error', 'Error deleting page. Please try again.');

		Router::redirect('admin/page/manage');
	}

}
