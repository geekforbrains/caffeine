<div class="area left short">
	<h3>Navigation</h3>
	<?php echo Menu::build('admin/admin/user', 0); ?>
</div>

<div class="area right">
	<h2>Create User</h2>
		
	<form method="post" action="<?php Router::url('admin/user/create'); ?>">
		<ul>
			<li class="text small">
				<label>Username</label>
				<input type="text" name="username" />
			</li>
			<li class="text small">
				<label>Password</label>
				<input type="password" name="pass" />
			</li>
			<li class="text small">
				<label>Email</label>
				<input type="text" name="email" />
			</li>
			<li class="buttons">
				<input type="submit" value="Create User" />
			</li>
		</ul>
	</form>
</div>
