<h1>Login</h1>

<form method="post" action="<?php echo Router::url('admin/login'); ?>">
	Username: <input type="text" name="username" /><br />
	Password: <input type="password" name="pass" /><br />

	<input type="submit" value="Login" />
</form>
