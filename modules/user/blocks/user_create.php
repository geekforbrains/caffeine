<h2>User Create</h2>

<form method="post" action="<?php Router::url('admin/user/create'); ?>">
	Username: <input type="text" name="username" /><br />
	Pass: <input type="password" name="pass" /><br />
	Email: <input type="text" name="email" /><br />

	<input type="submit" value="Create User" />
</form>
