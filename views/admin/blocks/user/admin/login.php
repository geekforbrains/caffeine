<div class="area">
	<h2>User Login</h2>
	<form method="post" action="<?php echo Router::url('admin/login'); ?>">
		<fieldset>
			<ul>
				<li class="text small">
					<label>Username:</label>
					<input type="text" name="username" />
				</li>
				<li class="text small">
					<label>Password:</label>
					<input type="password" name="pass" />
				</li>
				<li>
					<input type="submit" value="Login" />
				</li>
			</ul>
		</fieldset>
	</form>
</div>
