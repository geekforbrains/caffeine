<?php
/**
 * =============================================================================
 * User_Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class User_Admin extends User_Model {

	public static function login()
	{
		if($_POST)
		{
			$user_id = self::check_login(
				$_POST['username'],
				$_POST['pass'],
				Caffeine::get_site()
			);
			
			if($user_id)
			{
				$_SESSION['user'] = $user_id;
				Router::redirect(USER_LOGIN_REDIRECT);
			}
			else
				Message::set('error', 'Invalid login details.');
		}
		
		View::load('User_Admin', 'user_admin_login');
	}

	public static function logout() 
	{
		unset($_SESSION['user']);
		Message::store('success', 'You have been successfully logged out.');
		Router::redirect(USER_LOGOUT_REDIRECT);
	}


	public static function manage()
	{
		View::load('User_Admin', 'user_admin_manage',
			array('users' => User::get_all()));
	}

	public static function create()
	{
		if($_POST)
		{
			if(self::username_exists($_POST['username']))
				Message::set('error', 'That username is already in use.');
			else
			{
				if(self::add_user(
					$_POST['username'], 
					$_POST['pass'], 
					$_POST['email'],
					Caffeine::get_site()))
				{
					Message::store('success', 'User created successfully.');
					Router::redirect('admin/user/manage');
				}
			}
		}

		View::load('User_Admin', 'user_admin_create');
	}

	public static function edit($user_id)
	{
		if($_POST)
		{
			if(!isset($_POST['roles']))
				$_POST['roles'] = array();

			self::update_roles($_POST['user_id'], $_POST['roles']);
			Message::set('success', 'User roles updated successfully.');
		}

		View::load('User_Admin', 'user_admin_edit',
			array(
				'user' => User::get_by_id($user_id),
				'avail_roles' => Auth_Roles::get_all()
			)
		);
	}


}
