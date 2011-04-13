<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User_Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class User_Admin extends User_Model {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function login()
	{
		if($_POST)
		{
			$user_id = self::check_login(
				$_POST['username'],
				$_POST['pass'],
				User::current_site()
			);
			
			if($user_id)
			{
				$_SESSION['user'] = $user_id;
				$_SESSION['timeout'] = time(); // Set initial timestamp at login
				Router::redirect(USER_LOGIN_REDIRECT);
			}
			else
				Message::set('error', 'Invalid login details.');
		}
		
		View::load('User', 'admin/login');
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function logout() 
	{
		unset($_SESSION['user']);
		Message::store('success', 'You have been successfully logged out.');
		Router::redirect(USER_LOGOUT_REDIRECT);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function manage()
	{
		View::load('User', 'admin/manage',
			array('users' => User_Model::get_all()));
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function create()
	{
		if($_POST)
		{
			if($_POST['username'] == USER_ROOT_USERNAME)
				Message::set(MSG_ERR, 'That username cannot be used. Please choose a different one.');

			elseif(self::username_exists($_POST['username']))
				Message::set(MSG_ERR, 'That username is already in use.');

			else
			{
				User_Model::add_user(
					$_POST['username'],
					$_POST['pass'],
					$_POST['email'],
					User::current_site()
				);

				Message::store(MSG_OK, 'User created successfully.');
				Router::redirect('admin/admin/user/manage');
			}
		}

		View::load('User', 'admin/create');
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function edit($cid)
	{
		if($_POST)
		{
			if(!isset($_POST['is_admin']))
				$_POST['is_admin'] = 0;

			if(!isset($_POST['roles']))
				$_POST['roles'] = array();

			self::update_user($cid, $_POST['username'], $_POST['email'], $_POST['is_admin']);
			self::update_roles($cid, $_POST['roles']);

			// If password field is set, create new password
			if(strlen($_POST['pass']))
				self::update_pass($cid, $_POST['pass']);

			Message::set('success', 'User updated successfully.');
		}

		View::load('User', 'admin/edit',
			array(
				'user' => User_Model::get_by_cid($cid),
				'avail_roles' => Auth_Model_Roles::get_all()
			)
		);
	}

}
